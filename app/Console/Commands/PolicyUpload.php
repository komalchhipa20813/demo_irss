<?php

namespace App\Console\Commands;

use App\Models\Agent;
use App\Models\Bank;
use App\Models\BranchImdName;
use App\Models\Company;
use App\Models\CompanyBranch;
use App\Models\Customer;
use App\Models\Fdo;
use App\Models\HealthPolicy;
use App\Models\HealthPolicyPayment;
use App\Models\Make;
use App\Models\MotorPolicy;
use App\Models\MotorPolicyPayment;
use App\Models\MotorPolicyVehical;
use App\Models\Product;
use App\Models\ProductModel;
use App\Models\Settings;
use App\Models\SmePolicy;
use App\Models\SmePolicyPayment;
use App\Models\SubProduct;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PolicyUpload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'policy_upload';

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
        try{
            $start = now();
            $this->comment('Processing '.$start);
            // $files=['assets/AUG-policy.csv','assets/SEP-policy.csv','assets/OCT-policy.csv'];
            // foreach($files as $aa){
                // $motor_policy[]=[];
                $file = public_path('assets/dec-policy.csv');
                $policyArray = csvToArray($file);
                $agentCode = collect(Agent::get())->keyBy('code');
                $productName = collect(Product::get())->keyBy('name');
                $companyName = collect(Company::get())->keyBy('name');
                $IMDName = collect(BranchImdName::get())->keyBy('name');
                $makeName = collect(Make::get())->keyBy('name');
                $modelName = collect(ProductModel::get())->keyBy('name');
                $bankName = collect(Bank::get())->keyBy('name');
                $i=count(MotorPolicy::get())+1;$j=count(HealthPolicy::get())+1;$k=count(SmePolicy::get())+1;
                foreach($policyArray as $value){
                    if($value['PREFIX']==1){
                        $name = explode(' ',$value['Customer_Name']);
                        $first_name= (isset($name) && count($name) >= 1  ? $name[0] : '');
                        $middle_name= (isset($name) && count($name) >= 3  ? $name[1] : '');
                        $last_name= (isset($name) && count($name) >= 2  ? (count($name) >= 3?$name[2]:$name[1]) : '');
                        $customer=Customer::Where('first_name',$first_name)->where('middle_name',$middle_name)->where('last_name',$last_name)->first();
                        if(empty($customer)){
                            $customer_code=Settings::where('key','customer_code')->first()->value;
                            $customer = Customer::create([
                                'customer_code'=>$customer_code,
                                'first_name' => $first_name,
                                'middle_name'=> $middle_name,
                                'last_name' => $last_name,
                            ]);
                            Settings::where('key','customer_code')->update(['value'=>$customer_code+1]);
                        }
                    }else{
                        $customer=Customer::Where('first_name',$value['Customer_Name'])->first();
                        if(empty($customer)){
                            $customer_code=Settings::where('key','customer_code')->first()->value;
                            $customer = Customer::create([
                                'customer_code'=>$customer_code,
                                'first_name' => $value['Customer_Name'] ,
                                'middle_name'=> null,
                                'last_name' => null,
                                'prefix'=> 'M/S',
                            ]);
                            Settings::where('key','customer_code')->update(['value'=>$customer_code+1]);
                        }
                    }
                    $settings=Settings::where('key','policy_no');
                    $inward_number=inwardFirstChar(strtolower($value['Policy_Type'])).str_pad(1,2, '0', STR_PAD_LEFT).'GJ5'.Carbon::now()->format('y').'RS'.$settings->first()->value;
                    switch ($value['VERTICAL']) {
                        case "MOTOR":
                            $motor_policy[]=[
                                'inward_no'=>$inward_number,
                                "policy_type"=>$value['Policy_Type'],
                                'product_id' => (isset($value['Product_Name']) && isset($productName[$value['Product_Name']]) ? $productName[$value['Product_Name']]['id'] : null ),
                                'irss_branch_id' => 1,
                                'business_date' => '12-2022',
                                'customer_id' => $customer->id,
                                'agent_id' =>(isset($value['AGENT CODE']) && isset($agentCode[strtoupper($value['AGENT CODE'])]) ? $agentCode[strtoupper($value['AGENT CODE'])]['id'] : null ),
                                'company_id' => (isset($value['Co__Name']) && isset($companyName[$value['Co__Name']]) ? $companyName[$value['Co__Name']]['id'] : null ),
                                'company_branch_id' => (isset($value['Co__Name']) && isset($companyName[($value['Co__Name'])]) ? CompanyBranch::where('company_id',$companyName[$value['Co__Name']]['id'])->first()->id : null ),
                                'branch_imd_id' => (isset($value['IMD_Code___Name']) && isset($IMDName[$value['IMD_Code___Name']]) ? $IMDName[$value['IMD_Code___Name']]['id'] : null ),
                                'sub_product_id' =>(isset($value['Product_Name']) && isset($value['Sub_Product_Name']) && isset($productName[$value['Product_Name']]) && !empty(SubProduct::where('product_id',$productName[$value['Product_Name']]['id'])->where('name',$value['Sub_Product_Name'])->first())? SubProduct::where('product_id',$productName[$value['Product_Name']]['id'])->where('name',$value['Sub_Product_Name'])->first()->id : null ),
                                'policy_tenure' => $value['Policy_Tenure'],
                                'policy_number'=>$value['Policy_NO'],
                                'issue_date'=>!empty($value['Policy_Issue_Date'])?$value['Policy_Issue_Date']:'',
                                'start_date'=>!empty($value['OD_Policy_Start_Date'])?$value['OD_Policy_Start_Date']:'',
                                'end_date'=>!empty($value['OD_Policy_End_Date'])?$value['OD_Policy_End_Date']:'',
                                'total_premium'=>$value['GROSS_PREMIUM'],
                                'total_idv' => $value['Total_IDV'],
                                'discount' => $value['Discount'],
                                'ncb'=>$value['NCB'],
                                "is_od_only" =>2,
                                'od'=>$value['TOTAL OD'],
                                'pay_to_owner' => 0,
                                'tp'=>$value['TP'],
                                "is_gst_value" =>2,
                                'gst'=>$value['GstValue'],
                                'remark'=>$value['Remark'],
                                'created_at' => Carbon::parse('2022-12-15')->format('Y-m-d H:i:s'),
                                'updated_at' => Carbon::parse('2022-12-15')->format('Y-m-d H:i:s')
                            ];
                            $motor_policy_vehicles[]=[
                                'policy_id'=>$i,
                                'new_registration_no'=>0,
                                'registration_no'=>realVehicleNo($value['Registration_No']),
                                'engine_no'=>$value['Engine_No'],
                                'chasiss_no'=>$value['Chasis_No'],
                                'make_id'=>(isset($value['Make']) && isset($makeName[$value['Make']]) ? $makeName[$value['Make']]['id'] : null ),
                                'model_id' => (isset($value['Model']) && isset($modelName[$value['Model']]) ? $modelName[$value['Model']]['id'] : null ),
                                'cc_gvw_no'=>$value['CC / GVW'],
                                'manufacturing_year'=>$value['Manufacturing__Year'],
                                'fuel_type'=>$value['Fuel_Type'],
                                'created_at' => Carbon::parse('2022-12-15')->format('Y-m-d H:i:s'),
                                'updated_at' => Carbon::parse('2022-12-15')->format('Y-m-d H:i:s')
                            ];
                            $motor_policy_payments[]=[
                                'payment_type'=>4,
                                'policy_id'=> $i++,
                                'bank_id' => (isset($value['Bank_Name']) && isset($bankName[$value['Bank_Name']]) ? $bankName[$value['Bank_Name']]['id'] : null ),
                                'amount' => !empty($value['Amount'])?$value['Amount']:0,
                                'account_number' => null,
                                'number' => $value['ChequeNo'],
                                'payment_date' => !empty($value['ChequeDate'])?$value['ChequeDate']:'',
                                'created_at' => Carbon::parse('2022-12-15')->format('Y-m-d H:i:s'),
                                'updated_at' => Carbon::parse('2022-12-15')->format('Y-m-d H:i:s')
                            ];
                        break;
                        case "HEALTH":
                        $health_policy[]=[
                            'inward_no'=>$inward_number,
                            "policy_type"=>$value['Policy_Type'],
                            "product_id"=>(isset($value['Product_Name']) && isset($productName[$value['Product_Name']]) ? $productName[$value['Product_Name']]['id'] : null ),
                            "customer_id"=>$customer->id,
                            "irss_branch_id"=>1,
                            "company_id"=>(isset($value['Co__Name']) && isset($companyName[$value['Co__Name']]) ? $companyName[$value['Co__Name']]['id'] : null ),
                            "company_branch_id"=>(isset($value['Co__Name']) && isset($companyName[($value['Co__Name'])]) ? CompanyBranch::where('company_id',$companyName[$value['Co__Name']]['id'])->first()->id : null ),
                            "branch_imd_id"=>(isset($value['IMD_Code___Name']) && isset($IMDName[$value['IMD_Code___Name']]) ? $IMDName[$value['IMD_Code___Name']]['id'] : null ),
                            'sub_product_id' =>(isset($value['Product_Name']) && isset($value['Sub_Product_Name']) && isset($productName[$value['Product_Name']]) && !empty(SubProduct::where('product_id',$productName[$value['Product_Name']]['id'])->where('name',$value['Sub_Product_Name'])->first())? SubProduct::where('product_id',$productName[$value['Product_Name']]['id'])->where('name',$value['Sub_Product_Name'])->first()->id : null ),
                            "business_date"=>'12-2022',
                            "agent_id"=>(isset($value['AGENT CODE']) && isset($agentCode[strtoupper($value['AGENT CODE'])]) ? $agentCode[strtoupper($value['AGENT CODE'])]['id'] : null ),
                            "policy_number"=>$value['Policy_NO'],
                            "issue_date"=>!empty($value['Policy_Issue_Date'])?$value['Policy_Issue_Date']:'',
                            "start_date"=>!empty($value['OD_Policy_Start_Date'])?$value['OD_Policy_Start_Date']:'',
                            "end_date"=>!empty($value['OD_Policy_End_Date'])?$value['OD_Policy_End_Date']:'',
                            "policy_tenure"=>$value['Policy_Tenure'],
                            "od"=>$value['TOTAL OD'],
                            "is_gst_value" =>2,
                            "gst"=>$value['GstValue'],
                            "total_premium"=>$value['GROSS_PREMIUM'],
                            "remark"=>$value['Remark'],
                            'created_at' => Carbon::parse('2022-12-15')->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::parse('2022-12-15')->format('Y-m-d H:i:s')
                        ];
                        $health_policy_payments[]=[
                            'payment_type'=>4,
                            'policy_id'=> $j++,
                            'bank_id' => (isset($value['Bank_Name']) && isset($bankName[$value['Bank_Name']]) ? $bankName[$value['Bank_Name']]['id'] : null ),
                            'amount' => !empty($value['Amount'])?$value['Amount']:0,
                            'account_number' => null,
                            'number' => $value['ChequeNo'],
                            'payment_date' => !empty($value['ChequeDate'])?$value['ChequeDate']:'',
                            'created_at' => Carbon::parse('2022-12-15')->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::parse('2022-12-15')->format('Y-m-d H:i:s')
                        ];
                        break;
                        case "SME":
                            $sme_policy[]=[
                                'inward_no'=>$inward_number,
                                "policy_type"=>$value['Policy_Type'],
                                "product_id"=>(isset($value['Product_Name']) && isset($productName[$value['Product_Name']]) ? $productName[$value['Product_Name']]['id'] : null ),
                                "business_date"=>'12-2022',
                                "agent_id"=>(isset($value['AGENT CODE']) && isset($agentCode[strtoupper($value['AGENT CODE'])]) ? $agentCode[strtoupper($value['AGENT CODE'])]['id'] : null ),
                                "customer_id"=>$customer->id,
                                "irss_branch_id"=>1,
                                "company_id"=>(isset($value['Co__Name']) && isset($companyName[$value['Co__Name']]) ? $companyName[$value['Co__Name']]['id'] : null ),
                                "company_branch_id"=>(isset($value['Co__Name']) && isset($companyName[($value['Co__Name'])]) ? CompanyBranch::where('company_id',$companyName[$value['Co__Name']]['id'])->first()->id : null ),
                                "branch_imd_id"=>(isset($value['IMD_Code___Name']) && isset($IMDName[$value['IMD_Code___Name']]) ? $IMDName[$value['IMD_Code___Name']]['id'] : null ),
                                'sub_product_id' =>(isset($value['Product_Name']) && isset($value['Sub_Product_Name']) && isset($productName[$value['Product_Name']]) && !empty(SubProduct::where('product_id',$productName[$value['Product_Name']]['id'])->where('name',$value['Sub_Product_Name'])->first())? SubProduct::where('product_id',$productName[$value['Product_Name']]['id'])->where('name',$value['Sub_Product_Name'])->first()->id : null ),
                                "policy_number"=>$value['Policy_NO'],
                                "issue_date"=>!empty($value['Policy_Issue_Date'])?$value['Policy_Issue_Date']:'',
                                "policy_tenure"=>$value['Policy_Tenure'],
                                "start_date"=>!empty($value['OD_Policy_Start_Date'])?$value['OD_Policy_Start_Date']:'',
                                "end_date"=>!empty($value['OD_Policy_End_Date'])?$value['OD_Policy_End_Date']:'',
                                "discount"=>$value['Discount'],
                                "od"=>$value['TOTAL OD'],
                                "terrorism_premium"=>$value['TERRORISM'],
                                "is_gst_value" =>2,
                                "gst"=>$value['GstValue'],
                                "total_premium"=>$value['GROSS_PREMIUM'],
                                "occupancies"=>$value['occupancies'],
                                "remark"=>$value['Remark'],
                                'created_at' => Carbon::parse('2022-12-15')->format('Y-m-d H:i:s'),
                                'updated_at' => Carbon::parse('2022-12-15')->format('Y-m-d H:i:s')
                            ];
                            $sme_policy_payments[]=[
                                'payment_type'=>4,
                                'policy_id'=> $k++,
                                'bank_id' => (isset($value['Bank_Name']) && isset($bankName[$value['Bank_Name']]) ? $bankName[$value['Bank_Name']]['id'] : null ),
                                'amount' => !empty($value['Amount'])?$value['Amount']:0,
                                'account_number' => null,
                                'number' => $value['ChequeNo'],
                                'payment_date' => !empty($value['ChequeDate'])?$value['ChequeDate']:'',
                                'created_at' => Carbon::parse('2022-12-15')->format('Y-m-d H:i:s'),
                                'updated_at' => Carbon::parse('2022-12-15')->format('Y-m-d H:i:s')
                            ];
                        break;
                    }
                    $settings->update(['value'=>str_pad(++$settings->first()->value,6,'0',STR_PAD_LEFT)]);
                }
                $chunks = array_chunk($motor_policy,500);
                foreach($chunks as $chunk){
                    MotorPolicy::insert($chunk);
                }
                $chunks = array_chunk($health_policy,500);
                foreach($chunks as $chunk){
                    HealthPolicy::insert($chunk);
                }
                $chunks = array_chunk($sme_policy,500);
                foreach($chunks as $chunk){
                    SmePolicy::insert($chunk);
                }
                $chunks = array_chunk($motor_policy_vehicles,500);
                foreach($chunks as $chunk){
                    MotorPolicyVehical::insert($chunk);
                }
                $chunks = array_chunk($motor_policy_payments,500);
                foreach($chunks as $chunk){
                    MotorPolicyPayment::insert($chunk);
                }
                $chunks = array_chunk($health_policy_payments,500);
                foreach($chunks as $chunk){
                    HealthPolicyPayment::insert($chunk);
                }
                $chunks = array_chunk($sme_policy_payments,500);
                foreach($chunks as $chunk){
                    SmePolicyPayment::insert($chunk);
                }
            // }
            $time = $start->diffInSeconds(now());
            $this->comment("Processed in ".$time." seconds");
        }catch(\Throwable $e){
            dd($e);
        }
    }
}
