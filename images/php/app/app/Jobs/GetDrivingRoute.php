<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Redis;

class GetDrivingRoute implements ShouldQueue
{
    /*
    |--------------------------------------------------------------------------
    | Queueable Jobs
    |--------------------------------------------------------------------------
    |
    | This job base class provides a central location to place any logic that
    | is shared across all of your jobs. The trait included with the class
    | provides access to the "queueOn" and "delay" queue helper methods.
    |
    */

    use InteractsWithQueue, Queueable, SerializesModels;

    protected $token;
    protected $data;

    public function __construct ($token, $data) {
        $this->token = $token;
        $this->data = $data;
        error_log('dispatched');
    }

    public function handle (){

        $path = $this->data['path'];
        $base_url = "https://maps.googleapis.com/maps/api/distancematrix/json?";

        $data_array = array(
          'origins' => '',
          'destinations' => '',
          'departure_time' => time(),
          'traffic_model' => 'best_guess',
          'key' => env('GOOGLE_MAP_KEY'),
        );

        $count = 0;
        $total_distance = 0;
        $total_time = 0;
        $redis = Redis::connection();

        for ($i = 0; $i < sizeof($path)-1; $i++) {

            error_log('running: ' . $count);
            $count++;

            $data_array['origins'] = join(',', $path[$i]);
            $data_array['destinations'] = join(',', $path[$i+1]);

            $curl_data = http_build_query($data_array);
            // error_log('url: ' . $base_url.$curl_data);return;

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $base_url.$curl_data,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET"
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                $this->data['error'] = 'Network error: ' . $err;
                $this->data['status'] = 'failure';
                unset($this->data['path']);
                $redis->set($this->token, json_encode($this->data));
                return;
            }

            $google_result = json_decode($response);

            if ($google_result->status !== 'OK') {
                $this->data['error'] = 'API error: ' . $google_result->error_message;
                $this->data['status'] = 'failure';
                unset($this->data['path']);
                $redis->set($this->token, json_encode($this->data));
                return;
            }

            $value = $google_result->rows[0]->elements[0];

            if ($value->status == 'OK') {
                $total_distance += $value->distance->value;
                $total_time += $value->duration_in_traffic->value;
            } else {
                $this->data['error'] = 'API error: ' . $value->status;
                $this->data['status'] = 'failure';
                unset($this->data['path']);
                $redis->set($this->token, json_encode($this->data));
                return;
            }

            sleep(2);

        }

        $this->data['total_distance'] = $total_distance;
        $this->data['total_time'] = $total_time;
        $this->data['status'] = 'success';
        $redis->set($this->token, json_encode($this->data));
        return;


    }
}
