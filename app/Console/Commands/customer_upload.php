<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Settings;
use Illuminate\Console\Command;

class customer_upload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer_upload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $start = now();
        $this->comment('Processing '.$start);
        $file = public_path('assets/customerList.csv');
        $customer_data = csvToArray($file);
        $customerData = [];
        try{
            foreach($customer_data as $value){
                $customer_code=Settings::where('key','customer_code')->first()->value;
                Settings::where('key','customer_code')->update(['value'=>$customer_code+1]);
                if($value['customer_status']==0){
                    $name = explode(' ',$value['Customer_Name']);
                    $customerData[] = [
                        'customer_code'=>$customer_code,
                        'first_name' => (isset($name) && count($name) >= 1  ? $name[0] : ''),
                        'middle_name'=> (isset($name) && count($name) >= 3  ? $name[1] : ''),
                        'last_name' => (isset($name) && count($name) >= 2  ? (count($name) >= 3?$name[2]:$name[1]) : ''),
                        'prefix'=> ' ',
                    ];
                }else{
                    $customerData[] = [
                        'customer_code'=>$customer_code,
                        'first_name' => $value['Customer_Name'] ,
                        'middle_name'=> null,
                        'last_name' => null,
                        'prefix'=> 'M/S',
                    ];
                }

            }
            $chunks = array_chunk($customerData,500);
            foreach($chunks as $chunk){
                Customer::insert($chunk);
            }
            $time = $start->diffInSeconds(now());
            $this->comment("Processed in ".$time." seconds");
        }catch(\Throwable $e){
        }
    }
}
