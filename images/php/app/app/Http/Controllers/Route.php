<?php

namespace App\Http\Controllers;

use App\Jobs\GetDrivingRoute;
use Illuminate\Http\Request;
use App\Http\Controllers\Error;
use Illuminate\Support\Facades\Redis;

class Route
{

    private $request;
    private $uniqid;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->uniqid = $this->getUUID();
    }

    public function submit()
    {
        $input = $this->request->input();

        // Handling input format not correct error
        if (gettype($input) !== 'array' || sizeof($input) === 0) return Error::$input_not_found;
        if (sizeof($input) === 1) return Error::$input_dropoff_missing;
        if (sizeof($input) > env('MAX_TIMEOUT')/3) return Error::$input_size_exceeds;


        foreach ($input as $value) {

            // Handling child format not correct error
            if (
                gettype($value) !== 'array'
                || sizeof($value) !== 2
            ) {
                return Error::$input_latlong_wrong_format;
            }
                        // Handling child format not correct error (not array input)
            if (
                !isset($value[0])
                || !isset($value[1])
            ) {
                return Error::$input_latlong_missing;
            }

            if (
                !is_numeric($value[0])
                || !is_numeric($value[1])
            ) {
                return Error::$input_latlong_type;
            }
        }



        $redis = Redis::connection();

        $data = array('status' => 'in progress', 'path' => $input);
        if(!$redis->set($this->uniqid, json_encode($data))) {
            return Error::$database_error;
        }

        dispatch(new GetDrivingRoute($this->uniqid, $data));

        return array('token' => $this->uniqid);
    }

    public function check($token)
    {
        $redis = Redis::connection();
        if ($json = $redis->get($token)) {
            $json = json_decode($json);
            return response()->json($json);
        } else {
            return Error::$database_row_not_found;
        }

    }

    private function getUUID ()
    {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,

            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }
}
