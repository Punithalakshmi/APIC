<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Dealer;
use App\Models\Apilogs;
use App\Mail\CommonMail;
use App\Mail\RefreshTokenMail;
use App\Mail\TokenNotRefreshed;
use Mail;

class CoohomCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coohom:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh Token';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dealersLists = Dealer::where(array("status" => 1))->get()->toArray();

        if(is_array($dealersLists) && count($dealersLists) > 0)
        {
           
           foreach($dealersLists as $d => $dealers ){
          //  echo "<br />";
         //   echo $dealers['id']; 
            $apiData    = array();
            $appUid                = $dealers['appuid'];
            $apiData['name']       = $dealers['name'];
            $apiData['id']         = $dealers['id'];
            $apiData['email']      = $dealers['email'];
            $apiData['app_uid']    = $appUid;
           // $apiData['type']       = "Register";
         //   $apiData['url']        = getRegisterApiUrl();
        
            //$apiRes = api_request($apiData);

           // if($apiRes){
            
                $apiData['type']   = "Login";
                $apiData['url']    = getLoginApiUrl();
                $apiLoginres       = api_request($apiData);

            if($apiLoginres){
            
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

                saveApiLogs($apiData['url'],'Cron: Token has been refreshed Successfully',$dealers['id'],json_encode($apiLoginres)); 
            
                if ($data) {
                
                    $mailData = array(
                        'title' => 'Token has been refreshed Successfully - '.$dealers['name'],
                        'link'  => $link,
                        'name' => $dealers['name']
                    );
                    
                  //  Mail::to($dealers['email'])->send(new RefreshTokenMail($mailData));
                    $mailMessage = 'Cron: Sent mail to Dealer '.$dealers['name'];
                    saveApiLogs($apiData['url'],'Mail Sent',$dealers['id'],$mailMessage); 
                }
            }
            else
            {
                $mailData = array(
                    'title' => 'The token has not been refreshed. - '.$dealers['name'],
                    'link'  => '',
                    'name' => $dealers['name']
                );

                Mail::to('punitha@izaaptech.in')->send(new TokenNotRefreshed($mailData));
            }
        } 
    }
}

}
