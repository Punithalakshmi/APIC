<?php

use App\Models\ApiAuthentication;
use App\Models\Apilogs;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

if (!function_exists('greet_user')) {
    function greet_user($name)
    {
        return "Hello, " . ucfirst($name) . "!";
    }
}

if (!function_exists('format_date')) {
    function format_date($date, $format = 'Y-m-d')
    {
        return \Carbon\Carbon::parse($date)->format($format);
    }
}

if (!function_exists('generate_app_uid')) {
    /**
     * Generate a random string of specified length.
     *
     * @param int $length Length of the random string.
     * @return string
     */
    function generate_app_uid($length = 10)
    {
        return Str::random($length);
    }
}

if (!function_exists('current_timestamp_milliseconds')) {
    /**
     * Get the current timestamp in milliseconds since the Unix epoch.
     *
     * @return int
     */
    function current_timestamp_milliseconds()
    {
       // Generate a timestamp 2 minutes in the future in milliseconds
         return round(microtime(true) * 1000);
    }
}

if (!function_exists('generateTimestampAndSign')) {
function generateTimestampAndSign($apiData)
{
    // Get the current Unix timestamp in milliseconds
    $timestamp = round(microtime(true) * 1000);

    // Generate the sign (hash) using the secret key and timestamp
    $sign = hash('md5',$apiData['app_secret'] . $apiData['app_key'].$apiData['app_uid'].$timestamp);

    // Return both timestamp and sign
    return array(
        'timestamp' => $timestamp,
        'sign' => $sign,
    );
}
}
if (!function_exists('api_request')) {
   
    function api_request($apiDataP=array())
    {

        $apiData = generateTimestampAndSign($apiDataP);
      //  print_r($apiData); die;
        //$apiUrl = $apiData['url'].'?appuid='.$apiData['app_uid'].'&appkey='.$apiData['app_key'].'&timestamp='.$apiData['timestamp'].'&sign='.$apiData['sign '];
     //   $timestamp  = round(microtime(true) * 1000);
       
       // $apiData['sign ']      = md5(trim($apiData['app_key']).trim($apiData['app_secret']).$apiData['app_uid'].$timestamp);
      //  $apiData['timestamp']  = $timestamp; 
        
        $apiUrl = $apiDataP['url'].'?appkey='.$apiDataP['app_key'].'&timestamp='.$apiData['timestamp'].'&appuid='.$apiDataP['app_uid'].'&sign='.$apiData['sign'];
      // $apiUrl = $apiData['url'].'?appuid='.$apiData['app_uid'];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post($apiUrl, [
            'name' => $apiDataP['name'],
            'email' => $apiDataP['email']
         //   'password' => generate_app_uid(8),
         //   'roleId' => 2
        ]);
        Apilogs::create(array("action" => $apiDataP['url'],
                              "action_type" => $apiDataP['type'],
                              "dealer_id"=> $apiDataP['id'],
                              "api_response"=> $response,
                              "created_at" => date("Y-m-d H:i:s")
                            ));
         $responseArr = json_decode($response,true);                   
        if (isset($responseArr['c']) && ($responseArr['c'] == 100003)) {
            // Handle successful response
           return true;
            
        } else {
            // Handle error response
            
            return false;
        }
        
    }
}

if (!function_exists('api_info')) {
 
    function api_info($apiData=array())
    {
         return ApiAuthentication::find(1)->toArray();
    }
}