<?php

namespace App\Http\Controllers;

class Error
{
  public static $input_not_found = ["error" => "Input not found or format not correct"];
  public static $input_dropoff_missing = ["error" => "Location format not correct (Dropoff missing)"];
  public static $input_latlong_wrong_format = ["error" => "Location format not correct (Latitude or Lontitude)"];
  public static $input_latlong_missing = ["error" => "Location format not correct (Latitude or Lontitude missing)"];
  public static $input_latlong_type = ["error" => "Location format not correct (Latitude or Lontitude type)"];
  public static $database_error = ["error" => "Database insert error"];
  public static $database_row_not_found = ["status" => "failure", "error" => "Route not found"];
  public static $input_size_exceeds = ["error" => "Input size exceeds"];
}
