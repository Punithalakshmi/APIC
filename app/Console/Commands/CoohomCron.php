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

	    $dealersLists = Dealer::where(array("status" => 1))->get();
        
        saveApiLogs('Cron','Cron: Total Dealer Record Count '.count($dealersLists),'1',json_encode($dealersLists)); 
        
        $customEmails = getCustomTriggerEmails();

        if(count($dealersLists) > 0)
        {
           foreach($dealersLists as $d => $dealers ){

            try 
            {

                $apiData    = array();
                $appUid                = $dealers['appuid'];
                $apiData['name']       = $dealers['name'];
                $apiData['id']         = $dealers['id'];
                $apiData['email']      = $dealers['email'];
                $apiData['app_uid']    = $appUid;

                $apiData['type']   = "Login";
                $apiData['url']    = getLoginApiUrl();
                $apiLoginres       = api_request($apiData);

                if(isset($apiLoginres['c']) && $apiLoginres['c'] != 0)
                  throw new Exception(ucfirst($apiLoginres['m']));

                //domainname
                $domainName= getCoohomDomain();
                $coohomUrl = (isset($apiLoginres['d']['url']))?$apiLoginres['d']['url']:"";   
                $token     = (isset($apiLoginres['d']['token']))?$apiLoginres['d']['token']:"";
                $dealer   = Dealer::findOrFail($dealers['id']);

                $link = 'https://'.$domainName.'/api/saas/openapi/v2/redirect?url=https://'.$domainName."/pub/saas/apps/project/list&token=".$token."&locale=en_US";

                $dealer->current_url = $link;
                $dealer->token       = $token;

                $dealer->is_token_generated = 'Yes';
                $dealer->time_of_url_generation = date("Y-m-d H:i:s");	
                $data = $dealer->save();

                saveApiLogs($apiData['url'],'Cron: Token has been refreshed Successfully',$dealers['id'],json_encode($apiLoginres)); 
            
                if ($data) {
                
                    $mailData = array(
                        'title' => 'Token has been refreshed Successfully - '.$dealers['name'],
                        'link'  => $link,
                        'name' => $dealers['name']
                    );
                    
                     Mail::to($dealers['email'])->send(new RefreshTokenMail($mailData));
                     Mail::to('punitha@izaaptech.in')->send(new RefreshTokenMail($mailData));
                     Mail::to($customEmails)->send(new RefreshTokenMail($mailData));
                     
                    $mailMessage = 'Cron: Sent mail to Dealer '.$dealers['name'];
                    saveApiLogs($apiData['url'],'Mail Sent',$dealers['id'],$mailMessage); 
                  }
                
              }
              catch(\Exception $e){
                $mailData = array(
                    'title' => 'The token has not been refreshed. - '.$dealers['name'].'Error: ' . $e->getMessage(),
                    'link'  => '',
                    'name' => $dealers['name']
                );
                Mail::to('punitha@izaaptech.in')->send(new TokenNotRefreshed($mailData));
                Mail::to($customEmails)->send(new RefreshTokenMail($mailData));
                saveApiLogs($apiData['url'],'Cron Error: Token Not Refreshed',$dealers['id'],$e->getMessage()); 
             }          
        } 
    }
}

}
