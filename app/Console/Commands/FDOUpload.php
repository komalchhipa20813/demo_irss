<?php

namespace App\Console\Commands;

use App\Models\Fdo;
use App\Models\FdoServiceBranch;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FDOUpload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fdo:upload';

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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Fdo::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $file = public_path('assets/FDOList.csv');
        $fdoArray = csvToArray($file);
        $fdoData = [];
        foreach($fdoArray as $key=>$value){
            $name = explode(' ',$value['NAME']);
            $fdoData[] = [
                'code' => $value['CODE'],
                'home_irss_branch_id'=>1,
                'first_name' => (isset($name) && count($name) >= 1  ? $name[0] : ''),
                'middle_name'=> (isset($name) && count($name) >= 2  ? $name[1] : ''),
                'last_name' => (isset($name) && count($name) >= 3  ? $name[2] : ''),
                'password'=>Hash::make('123456'),
                'phone'=>$value['MOB.NO.'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
            $pivot_data[]=[
                'fdo_id' => $key+1,
                'irss_branch_id' => 1
            ];
        }
        Fdo::insert($fdoData);
        FdoServiceBranch::insert($pivot_data);
    }
}
