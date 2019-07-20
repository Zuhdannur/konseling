<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;

class ExpiredReminder extends Command
{
   /**
    * The name and signature of the console command.
    *
    * @var string
    */
   protected $signature = 'expired:reminder';
   /**
    * The console command description.
    *
    * @var string
    */
   protected $description = 'Command description';
   /**
    * Create a new command instance.
    *
    * @return void
    */
   public function __construct()
   {
       parent::__construct();
   }
   /**
    * Execute the console command.
    *
    * @return mixed
    */
   public function handle()
   {
       
   }
}