<?php

use App\Models\ApiAuthentication;
use App\Models\Apilogs;
use App\Models\Dealer;
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
    if($apiData['type']=='Register')
      $sign = hash('md5',getApiSecretKey().getApiKey().$timestamp);
    else
       $sign = hash('md5',getApiSecretKey().getApiKey().$apiData['app_uid'].$timestamp);

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
    
        $apiUrl = $apiDataP['url'].'?appkey='.getApiKey().'&timestamp='.$apiData['timestamp'].'&appuid='.$apiDataP['app_uid'].'&sign='.$apiData['sign'];
      
        if($apiDataP['type'] == 'Delete'){
            $response = Http::delete($apiUrl);
        }
        else if($apiDataP['type'] == 'Register' || $apiDataP['type'] == 'Login'){
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post($apiUrl, [
                'name' => $apiDataP['name'],
                'email' => $apiDataP['email']
            ]);
        }
        else
        {
            $apiUrl = $apiDataP['url'].'?appkey='.getApiKey().'&timestamp='.$apiData['timestamp'].'&appuid='.$apiDataP['app_uid'].'&sign='.$apiData['sign'].'&role_id=3&active=true';
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->put($apiUrl, [
                'appuid' => $apiDataP['app_uid'],
                'role_id' => 3,
                'active' => true
            ]);
        }    
        
        saveApiLogs($apiDataP['url'],$apiDataP['type'],$apiDataP['id'],$response.'-'.$apiUrl);  
        $responseArr = json_decode($response,true);  

        if ((isset($responseArr['c']) && ($responseArr['c'] == 0) && ($responseArr['d'] == 'Coohom Register suceeded!')) || ($apiDataP['type']=='Login' && isset($responseArr['c']) && $responseArr['c'] == 0)) {
            // Handle successful response
           if($apiDataP['type'] == 'Register') 
              return true;
          else
              return $responseArr;
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

if (!function_exists('getApiKey')) {
 
    function getApiKey()
    {
         return env('COOHOM_API_KEY');
    }
}

if (!function_exists('getApiSecretKey')) {
 
    function getApiSecretKey()
    {
         return env('COOHOM_SECRET_KEY');
    }
}

if (!function_exists('getRegisterApiUrl')) {
 
    function getRegisterApiUrl()
    {
         return env('COOHOM_REGISTER_API_URL');
    }
}

if (!function_exists('getLoginApiUrl')) {
 
    function getLoginApiUrl()
    {
        return env('COOHOM_LOGIN_API_URL');
    }
}

if (!function_exists('getCoohomDomain')) {
 
    function getCoohomDomain()
    {
         return env('DOMAIN_NAME');
    }
}

if (!function_exists('saveApiLogs')) {
 
    function saveApiLogs($action,$type,$dealer_id,$response)
    {
        Apilogs::create(array("action" => $action,
        "action_type" => $type,
        "dealer_id"=> $dealer_id,
        "api_response"=> $response,
        "created_at" => date("Y-m-d H:i:s")
        ));

    }
}

if(!function_exists('getDeleteApiUrl')){

    function getDeleteApiUrl()
    {
        return env('COOHOM_DELETE_API_URL');
    }
}

if(!function_exists('getUpgradeApiUrl')){
    function getUpgradeApiUrl(){
        return env('COOHOM_UPGRADE_API_URL');
    }
}