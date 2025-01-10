<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\CommonMail;
use App\Mail\RefreshTokenMail;
use Mail;

class TestJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:fetch-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Cron';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $mailData = array(
            'title' => 'Cron Job Test',
            'link'  => '',
            'name' => 'Punitha',
            'title'=> 'Test Cron'
        );
        
        Mail::to('gowtham.izaap@gmail.com')->send(new CommonMail($mailData));
    }
}
