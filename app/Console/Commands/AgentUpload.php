<?php

namespace App\Console\Commands;

use App\Models\Agent;
use App\Models\Fdo;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class AgentUpload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agent:upload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $start = now();
        $this->comment('Processing '.$start);
        $file = public_path('assets/final_agent.csv');
        $agentArray = csvToArray($file);
        $agentData = [];
        $fdoCode = collect(Fdo::get())->keyBy('code');
        try{
            foreach($agentArray as $value){
                $name = explode(' ',$value['agentfullname']);
                $agentData[] = [
                    'fdo_id'=> (isset($value['fdocode']) && isset($fdoCode[strtoupper($value['fdocode'])]) ? $fdoCode[strtoupper($value['fdocode'])]['id'] : 0 ),
                    'code' => $value['agentcode'],
                    'home_irss_branch_id'=>1,
                    'email' => (isset($value['primaryemailid']) && $value['primaryemailid'] != 0 ? $value['primaryemailid'] : null),
                    'secondary_email' => (isset($value['secondaryemailid']) && $value['secondaryemailid'] != 0 ? $value['secondaryemailid'] : null),
                    'first_name' => (isset($name) && count($name) >= 1  ? $name[0] : ''),
                    'middle_name'=> (isset($name) && count($name) >= 2  ? $name[1] : ''),
                    'last_name' => (isset($name) && count($name) >= 3  ? $name[2] : ''),
                    'password'=>Hash::make('123456'),
                    'phone'=>$value['primarymobileno'],
                    'office_address'=>$value['officeaddress'],
                    'residential_address'=>$value['residentialaddress'],
                    'city' =>$value['cityname'],
                    'dob'=> (isset($value['birthdate']) ? date('Y-m-d', strtotime($value['birthdate'])) : null ),
                    'anniversary_date'=> (isset($value['annidate']) ? date('Y-m-d', strtotime($value['annidate'])) : null ),
                    'joining_date'=>null,
                    'effective_from'=> (isset($value['effectivefrom']) ? date('Y-m-d H:i:s', strtotime( $value['effectivefrom'])) : null ),
                    'account_number'=> (preg_replace('/[*\`]/', '', $value['accountnumber'])),
                    'ifsc_code'=> (isset($value['ifsccode']) && (str_replace('*','',$value['ifsccode'])) != NULL  ? (str_replace('*','',$value['ifsccode'])) : null ),
                    'bank_name'=>$value['bankname'],
                    'holder_name'=>$value['accountname']
                ];

            }
            $chunks = array_chunk($agentData,500);
            foreach($chunks as $chunk){
                Agent::insert($chunk);
            }
            $time = $start->diffInSeconds(now());
            $this->comment("Processed in ".$time." seconds");
        }catch(\Throwable $e){
        }
    }
}

