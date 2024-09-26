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
        if(empty($id)){
            abort(404, 'Dealer not found.');
        }

        $dealers = Dealer::findOrFail($id);
      
        $apiData    = array();
        $appUid                = generate_app_uid(6);
        $apiData['name']       = $dealers['name'];
        $apiData['id']         = $dealers['id'];
        $apiData['email']      = $dealers['email'];
        $apiData['app_uid']    = $appUid;
        $apiData['type']       = "Register";
        $apiData['url']        = getRegisterApiUrl();
       
        $apiRes = api_request($apiData);
        if($apiRes){
          
            $apiData['type']       = "Login";
            $apiData['url']        = getLoginApiUrl();
            $apiLoginres           = api_request($apiData);
         
             //domainname
            $domainName= getCoohomDomain();
            $coohomUrl = (isset($apiLoginres['d']['url']))?$apiLoginres['d']['url']:"";   
            $token     = (isset($apiLoginres['d']['token']))?$apiLoginres['d']['token']:"";
            $dealers   = Dealer::findOrFail($dealers['id']);

            $link = 'https://'.$domainName.'/api/saas/openapi/v2/redirect?url=https://'.$domainName."/pub/saas/apps/project/list&token=".$token."&locale=en_US";

            $dealers->current_url = $link;
            $dealers->token       = $token;

            $dealers->is_token_generated = 'Yes';
            $dealers->time_of_url_generation = date("Y-m-d H:i:s");	
            $data = $dealers->save();

            saveApiLogs($apiData['url'],'Token Generated Successfully',$dealers['id'],json_encode($apiLoginres)); 
           
            if ($data) {
               
                $mailData = array(
                    'title' => 'Token Generated Successfully',
                    'link'  => $link
                );
                   
                Mail::to($dealers['email'])->send(new CommonMail($mailData));
                session()->flash('success', 'Token Generated Successfully');
                
                saveApiLogs($apiData['url'],'Mail Sent',$dealers['id'],'Sent mail to Dealer'); 

                return redirect(route('admin/dealers'));
            }

        }
        else
        {
            session()->flash('error', 'Error in coohom register');
            
            saveApiLogs($apiData['url'],'Error: Coohom Register',$dealers['id'],'Error in Coohom Register'); 
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
