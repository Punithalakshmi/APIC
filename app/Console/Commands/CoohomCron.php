<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Dealer;

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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //get only active dealers 
        $dealers = Dealer::where(array("status" => 1));

        if(is_array($dealers)){
            
        }
    }
}
