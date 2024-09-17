<?php

namespace App\Http\Controllers;

use App\Models\ApiAuthentication;
use Illuminate\Http\Request;
use App\Models\Dealer;
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
        $apiInfo    = api_info();
        $appUid     = generate_app_uid(6);
        
        $apiData    = array();

        $apiData['name']       = $dealers['name'];
        $apiData['id']         = $dealers['id'];
        $apiData['email']      = $dealers['email'];
        //$apiData['app_key']    = trim($apiInfo['app_key']);
        //$apiData['app_secret'] = trim($apiInfo['app_secret']);
        $apiData['app_uid']    = $appUid;
        $apiData['app_key']    = env('APP_KEY');
        $apiData['app_secret'] = env('SECRET_KEY');
    
        $apiData['type']       = "Register";
        $apiData['url']        = "https://api.coohom.com/global/i18n-user/register";
       
        $apiRes = api_request($apiData);
        if($apiRes){
           // session()->flash('success', 'Register Successfully');
            $apiData['type']       = "Login";
            $apiData['url']        = "https://api.coohom.com/global/i18n-user/login";
            $apiLoginres = api_request($apiData);
             //convert json to array
           // $apiLoginres = json_decode($apiLoginRes,true);
             //domainname
            $domainName= env('DOMAIN_NAME');
            $coohomUrl = (isset($apiLoginres['d']['url']))?$apiLoginres['d']['url']:"";   
            $token     = (isset($apiLoginres['d']['token']))?$apiLoginres['d']['token']:"";
            $dealers   = Dealer::findOrFail($dealers['id']);

            $link = 'https://'.$domainName.'/api/saas/openapi/v2/redirect?url=https://'.$domainName.$coohomUrl."&token=".$token."&locale=en_US";

            $dealers->current_url = $link;
            $dealers->token       = $token;
           // $dealers->token       = $coohomUrl;
            $dealers->is_token_generated = 'Yes';
            $data = $dealers->save();
            if ($data) {
               
                $mailData = array(
                    'title' => 'Token Generated Successfully',
                    'link'  => $link
                );
                   
                Mail::to($dealers['email'])->send(new CommonMail($mailData));
                session()->flash('success', 'Token Generated Successfully');
                return redirect(route('admin/dealers'));
            }
           // return redirect(route('admin/dealers'));
        }
        else
        {
            session()->flash('error', 'Error in coohom register');
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
