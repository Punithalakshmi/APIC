<?php

namespace App\Http\Controllers;

use App\Models\ApiAuthentication;
use Illuminate\Http\Request;
use App\Models\Dealer;
use App\Models\Apilogs;
use App\Mail\CommonMail;
use Mail;

class ApiAuthenticationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function register($id)
    {
        //
        $dealers = Dealer::findOrFail($id);
      
        $apiData    = array();
        $appUid     = generate_app_uid(6);
        $apiData['name']       = $dealers['name'];
        $apiData['id']         = $dealers['id'];
        $apiData['email']      = $dealers['email'];
        $apiData['app_uid']    = $appUid;
        $apiData['app_key']    = env('COOHOM_API_KEY');
        $apiData['app_secret'] = env('COOHOM_SECRET_KEY');
        $apiData['type']       = "Register";
        $apiData['url']        = env('COOHOM_REGISTER_API_URL');;
       
        $apiRes = api_request($apiData);
        if($apiRes){
          
            $apiData['type']       = "Login";
            $apiData['url']        = env('COOHOM_LOGIN_API_URL');
            $apiLoginres           = api_request($apiData);
         
             //domainname
            $domainName= env('DOMAIN_NAME');
            $coohomUrl = (isset($apiLoginres['d']['url']))?$apiLoginres['d']['url']:"";   
            $token     = (isset($apiLoginres['d']['token']))?$apiLoginres['d']['token']:"";
            $dealers   = Dealer::findOrFail($dealers['id']);

            $link = 'https://'.$domainName.'/api/saas/openapi/v2/redirect?url=https://'.$domainName.$coohomUrl."&token=".$token."&locale=en_US";

            $dealers->current_url = $link;
            $dealers->token       = $token;

            $dealers->is_token_generated = 'Yes';
            $data = $dealers->save();

            Apilogs::create(array("action" => $apiData['url'],
                "action_type" => 'Token Generated Successfully',
                "dealer_id"=> $dealers['id'],
                "api_response"=> json_encode($apiLoginres),
                "created_at" => date("Y-m-d H:i:s")
            ));

            if ($data) {
               
                $mailData = array(
                    'title' => 'Token Generated Successfully',
                    'link'  => $link
                );
                   
                Mail::to($dealers['email'])->send(new CommonMail($mailData));
                session()->flash('success', 'Token Generated Successfully');
                Apilogs::create(array("action" => $apiData['url'],
                "action_type" => "Mail",
                "dealer_id"=> $dealers['id'],
                "api_response"=> "Sent mail to Dealer",
                "created_at" => date("Y-m-d H:i:s")
              ));
                return redirect(route('admin/dealers'));
            }

        }
        else
        {
            session()->flash('error', 'Error in coohom register');
            Apilogs::create(array("action" => $apiData['url'],
            "action_type" => "Error: Coohom Register",
            "dealer_id"=> $dealers['id'],
            "api_response"=> "Error in Coohom Register",
            "created_at" => date("Y-m-d H:i:s")
          ));
            return redirect(route('admin/dealers'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ApiAuthentication $apiAuthentication)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ApiAuthentication $apiAuthentication)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ApiAuthentication $apiAuthentication)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApiAuthentication $apiAuthentication)
    {
        //
    }
}
