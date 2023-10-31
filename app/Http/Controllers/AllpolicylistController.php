<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ExportUpdateAllPolicy;
use App\Models\{BranchImdName, SmePolicy,MotorPolicy, HealthPolicy,IrssBranch,Company,Product,Agent,MotorPolicyVehical};
use App\Notifications\PolicyAddNotification;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;


class AllpolicylistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list_all()
    {
        $data['irss_branches'] = IrssBranch::where('status',1)->get(['id','name']);
        $data['companies'] = Company::where('status',1)->get(['id','name']);
        $data['products'] = Product::where('status',1)->get(['id','name']);
        $data['agents'] = Agent::where('status',1)->get(['id','first_name','last_name','middle_name','prefix','code']);
        return view('pages.update-all-policy.all_policy_list',compact('data'));
    }
     public function listing(Request $request)
    {

        $records = [];
        $tenures = tenures();

        //Health policy Data
        $users = healthpolicy_data($request)->where('status','!=',3)->latest()->get(['id','product_id','customer_id','company_branch_id','agent_id','policy_number','inward_no','company_id','policy_tenure','branch_imd_id','issue_date','start_date','end_date','total_premium','od','policy_copy']); 
        if(isset($users) && !empty($users)){
            $sr=1;
            foreach ($users as $key => $row) {

                // branch HTMl
                $html='';
                $data_imd= BranchImdName::where("company_branch_id",$row->company_branch_id )->where('status',1)->get(["name", "id"]);
                
                if(!empty($data_imd))
                {
                    $html='<option selected disabled class="input-cstm">Select Branch</option>';
                    foreach ($data_imd as $key => $imd) {
                        $selected= ($imd->id == $row->branch_imd_id)? 'selected' : '';
                        $html.='<option '.$selected.' value="'.$imd->id.'">'.$imd->name.'</option>';
                    }
                }
                else
                {
                    $html='<option selected disabled class="input-cstm">Please First Enter Branch</option>';
                }

               

                //End branch
                //
                 $policy_tenure_html='<option selected disabled class="input-cstm">Select</option>';
                foreach ($tenures as $key => $tenure) {
                    if($tenure == 'ABOVE15YRS'){
                        $tenure_name='Above 15 Years';
                    }elseif($tenure == 'SHORT'){
                        $tenure_name='Short Period';
                    }else
                    {
                        $tenure_name= $tenure.' Year';
                    }
                    $selected= ($tenure == $row->policy_tenure)? 'selected' : '';
                    $policy_tenure_html.='<option '.$selected.' value="'.$tenure.'">'.$tenure_name.'</option>';
                }

                if(empty($row->policy_number) ||
                    empty($row->branch_imd_id) ||
                    empty($row->policy_tenure) ||
                    empty($row->issue_date) ||
                    empty($row->start_date) ||
                    empty($row->end_date) ||
                    empty($row->policy_copy)
                   )
                {
                    $records[] = array(
                        '0' =>'<input type="checkbox" class="checkbox"  name="checkid[]" id="' . encryptid($row->id) . '"  onclick="single_unselected(this);" data-series="'.$sr.'"   style="    margin-left: 8px;"/><input type="hidden" name="id_'.$sr.'" value="'.encryptid($row->id).'"><input type="hidden" name="policy_name_'.$sr.'" value="health">',
                        '1' => '<select class="form-select form-control branch_imd "  name="branch_imd_'.$sr.'" >'.$html.' </select>',
                        '2'=>(!empty($row->customer_id)) ?$row->customer->prefix.''.$row->customer->first_name.' '.$row->customer->middle_name .' '.$row->customer->last_name:'',
                         '3' =>$row->inward_no,
                        '4' => $row->product->name,
                        '5' => '<p>'.$row->od.'</p>',
                        '6' => $row->total_premium,
                        '7' => '<div class="input-group ">
                        <input type="text" name="issue_date_'.$sr.'" value="'.$row->issue_date.'" class="form-control datepicker issue_date" autocomplete="off" id="issue_date">
                        
                        </div>',
                        '8'=>'<select class="form-select form-control policy_tenure " id="policy_tenure_'.$sr.'" name="policy_tenure_'.$sr.'" onchange="addYears('.$sr.')">'.$policy_tenure_html.' </select>',
                        '9'=>'<div class="input-group ">
                        <input type="text" name="start_date_'.$sr.'" value="'.$row->start_date.'" class="form-control datepicker start_date" autocomplete="off" id="start_date_'.$sr.'" onchange="addYears('.$sr.')">
                        
                        </div>',
                        '10'=>'<div class="input-group ">
                        <input type="text" name="end_date_'.$sr.'" value="'.$row->end_date.'" class="form-control" autocomplete="off" id="end_date_'.$sr.'" readonly>
                        
                        </div>',
                        '11'=>'<input type="text" class="form-control" name="policy_number_'.$sr.'" id="policy_number"  autocomplete="off"  value="'.$row->policy_number.'">',
                        '12'=>'<input class="form-control" name="policy_copy_'.$sr.'" type="file" id="policy_copy" accept="application/pdf">',

                    );
                    $sr++;
                }
            }
        }
        //Motor policy Data
        $motorPolicy = motopolicy_data($request)->where('status','!=',3)->latest()->get();
        if(isset($motorPolicy) && !empty($motorPolicy)){
           
            foreach ($motorPolicy as $key => $row) {

                // branch HTMl
                $html='';
                $data_imd= BranchImdName::where("company_branch_id",$row->company_branch_id )->where('status',1)->get(["name", "id"]);
                
                if(!empty($data_imd))
                {
                    $html='<option selected disabled class="input-cstm">Select Branch</option>';
                    foreach ($data_imd as $key => $imd) {
                        $selected= ($imd->id == $row->branch_imd_id)? 'selected' : '';
                        $html.='<option '.$selected.' value="'.$imd->id.'">'.$imd->name.'</option>';
                    }
                }
                else
                {
                    $html='<option selected disabled class="input-cstm">Please First Enter Branch</option>';
                }

                //End branch
                //
                 $policy_tenure_html='<option selected disabled class="input-cstm">Select</option>';
                 foreach ($tenures as $key => $tenure) {
                    if($tenure == 'ABOVE15YRS'){
                        $tenure_name='Above 15 Years';
                    }elseif($tenure == 'SHORT'){
                        $tenure_name='Short Period';
                    }else
                    {
                        $tenure_name= $tenure.' Year';
                    }
                    $selected= ($tenure == $row->policy_tenure)? 'selected' : '';
                    $policy_tenure_html.='<option '.$selected.' value="'.$tenure.'">'.$tenure_name.'</option>';
                }

                if(empty($row->policy_number) ||
                empty($row->branch_imd_id) ||
                empty($row->policy_tenure) ||
                empty($row->issue_date) ||
                empty($row->start_date) ||
                empty($row->end_date) ||
                empty($row->policy_copy)
               )
                {
                $records[] = array(
                    '0' =>'<input type="checkbox" class="checkbox"  name="checkid[]" id="' . encryptid($row->id) . '"  onclick="single_unselected(this);" data-series="'.$sr.'"   style="    margin-left: 8px;"/><input type="hidden" name="id_'.$sr.'" value="'.encryptid($row->id).'"><input type="hidden" name="policy_name_'.$sr.'" value="motor">',
                    '1' => '<select class="form-select form-control branch_imd "  name="branch_imd_'.$sr.'" >'.$html.' </select>',
                    '2'=>(!empty($row->customer_id)) ?$row->customer->prefix.''.$row->customer->first_name.' '.$row->customer->middle_name .' '.$row->customer->last_name:'',
                     '3' =>$row->inward_no,
                    '4' => $row->product->name,
                    '5' => '<p>'.$row->od .'</p><p>'.$row->tp.'</p>',
                    '6' => $row->total_premium,
                    '7' => '<div class="input-group ">
                    <input type="text" name="issue_date_'.$sr.'" value="'.$row->issue_date.'" class="form-control datepicker issue_date" autocomplete="off" id="issue_date">
                    
                    </div>',
                    '8'=>'<select class="form-select form-control policy_tenure " id="policy_tenure_'.$sr.'" name="policy_tenure_'.$sr.'" onchange="addYears('.$sr.')">'.$policy_tenure_html.' </select>',
                    '9'=>'<div class="input-group ">
                    <input type="text" name="start_date_'.$sr.'" value="'.$row->start_date.'" class="form-control datepicker start_date" autocomplete="off" id="start_date_'.$sr.'" onchange="addYears('.$sr.')">
                    
                    </div>',
                    '10'=>'<div class="input-group ">
                    <input type="text" name="end_date_'.$sr.'" value="'.$row->end_date.'" class="form-control" autocomplete="off" id="end_date_'.$sr.'" readonly>
                    
                    </div>',
                    '11'=>'<input type="text" class="form-control" name="policy_number_'.$sr.'" id="policy_number"  autocomplete="off"  value="'.$row->policy_number.'">',
                    '12'=>'<input class="form-control" name="policy_copy_'.$sr.'" type="file" id="policy_copy" accept="application/pdf">',
                );



                $sr++;
            }
            }
        }
        //SME policy Data
        $sme_policies = smepolicy_data($request)->where('status','!=',3)->latest()->get(['id','product_id','customer_id','company_branch_id','policy_number','agent_id','inward_no','company_id','policy_tenure','branch_imd_id','issue_date','start_date','end_date','total_premium','od','policy_copy']);
        if(isset($sme_policies) && !empty($sme_policies)){
            foreach ($sme_policies as $key => $row) {

                // branch HTMl
                $html='';
                $data_imd= BranchImdName::where("company_branch_id",$row->company_branch_id )->where('status',1)->get(["name", "id"]);
                
                if(!empty($data_imd))
                {
                    $html='<option selected disabled class="input-cstm">Select Branch</option>';
                    foreach ($data_imd as $key => $imd) {
                        $selected= ($imd->id == $row->branch_imd_id)? 'selected' : '';
                        $html.='<option '.$selected.' value="'.$imd->id.'">'.$imd->name.'</option>';
                    }
                }
                else
                {
                    $html='<option selected disabled class="input-cstm">Please First Enter Branch</option>';
                }

               

                //End branch
                //
                 $policy_tenure_html='<option selected disabled class="input-cstm">Select</option>';
                 foreach ($tenures as $key => $tenure) {
                    if($tenure == 'ABOVE15YRS'){
                        $tenure_name='Above 15 Years';
                    }elseif($tenure == 'SHORT'){
                        $tenure_name='Short Period';
                    }else
                    {
                        $tenure_name= $tenure.' Year';
                    }
                    $selected= ($tenure == $row->policy_tenure)? 'selected' : '';
                    $policy_tenure_html.='<option '.$selected.' value="'.$tenure.'">'.$tenure_name.'</option>';
                }


                if(empty($row->policy_number) ||
                empty($row->branch_imd_id) ||
                empty($row->policy_tenure) ||
                empty($row->issue_date) ||
                empty($row->start_date) ||
                empty($row->end_date) ||
                empty($row->policy_copy)
               )
                {
                $records[] = array(
                    '0' =>'<input type="checkbox" class="checkbox"  name="checkid[]" id="' . encryptid($row->id) . '"  onclick="single_unselected(this);" data-series="'.$sr.'"   style="    margin-left: 8px;"/><input type="hidden" name="id_'.$sr.'" value="'.encryptid($row->id).'"><input type="hidden" name="policy_name_'.$sr.'" value="sme">',
                    '1' => '<select class="form-select form-control branch_imd "  name="branch_imd_'.$sr.'" >'.$html.' </select>',
                    '2'=>(!empty($row->customer_id)) ?$row->customer->prefix.''.$row->customer->first_name.' '.$row->customer->middle_name .' '.$row->customer->last_name:'',
                     '3' =>$row->inward_no,
                    '4' => !empty($row->product)?$row->product->name:'',
                    '5' => '<p>'.$row->od.'</p>',
                    '6' => $row->total_premium,
                    '7' => '<div class="input-group ">
                    <input type="text" name="issue_date_'.$sr.'" value="'.$row->issue_date.'" class="form-control datepicker issue_date" autocomplete="off" id="issue_date">
                    
                    </div>',
                    '8'=>'<select class="form-select form-control policy_tenure " id="policy_tenure_'.$sr.'" name="policy_tenure_'.$sr.'" onchange="addYears('.$sr.')">'.$policy_tenure_html.' </select>',
                    '9'=>'<div class="input-group ">
                    <input type="text" name="start_date_'.$sr.'" value="'.$row->start_date.'" class="form-control datepicker start_date" autocomplete="off" id="start_date_'.$sr.'" onchange="addYears('.$sr.')">
                    
                    </div>',
                    '10'=>'<div class="input-group ">
                    <input type="text" name="end_date_'.$sr.'" value="'.$row->end_date.'" class="form-control" autocomplete="off" id="end_date_'.$sr.'" readonly>
                    
                    </div>',
                    '11'=>'<input type="text" class="form-control" name="policy_number_'.$sr.'" id="policy_number"  autocomplete="off"  value="'.$row->policy_number.'">',
                    '12'=>'<input class="form-control" name="policy_copy_'.$sr.'" type="file" id="policy_copy" accept="application/pdf">',
                );
                $sr++;
            }
            }
        }
        return response(['data'=>$records]);
    }
    public function update_policy(Request $request)
    {
        $redirect_url='update-policy/list-all-policy';
         try{
             foreach (explode(',', $request->selected_data_no) as $key => $value) {
                $policy_name='policy_name_'.$value;
                $id='id_'.$value;
                $branch_imd='branch_imd_'.$value;
                $policy_number='policy_number_'.$value;
                $issue_date='issue_date_'.$value;
                $start_date='start_date_'.$value;
                $end_date='end_date_'.$value;
                $policy_tenure='policy_tenure_'.$value;
                $policy_copy='policy_copy_'.$value;
                if($request->$policy_name == 'sme'){
                    $redirect_url='update-policy/sme-policy-update';
                    $name=SmePolicy::where('id',decryptid($request->$id))->with('company')->first();
                    $url = $name->policy_copy;
                    $policy_copy_status=$name->policy_copy_status;
                    if($request->has('policy_number_'.$value)&&$request->has('policy_copy_'.$value)){
                        $url='policies/sme/'.str_replace(" ","_",$name->company->name).'_'.$name->company->id.'/'.str_replace('/', '', $request->$policy_number).'.pdf';
                        if (Storage::disk('s3')->exists($url)) 
                            Storage::disk('s3')->delete($url);
                        Storage::disk('s3')->put($url,file_get_contents($request->$policy_copy));
                        $policy_copy_status=2;
                    }
                    $sme_policy=SmePolicy::updateOrCreate([
                        'id' => decryptid($request->$id),
                    ],["branch_imd_id"=>(int)$request->$branch_imd,
                        "policy_copy"=>$url, // check policy upload or not
                        "policy_number"=>$request->$policy_number,
                        "issue_date"=>$request->$issue_date,
                        "start_date"=>$request->$start_date,
                        "end_date"=>$request->$end_date,
                        "policy_tenure"=>$request->$policy_tenure,
                        'policy_copy_status' =>$policy_copy_status,
                        "updated_by_id" => Auth::user()->id,
                    ]);  
                    $sme_policy=$sme_policy->toArray();
                    $sme_policy['policy_type']='SME';
                    $sme_policy['notification_type']='book';
                    $sme_policy['message']='Updated SME Policy('.$sme_policy['inward_no'].') Recieved!';
                    Agent::where('id',$name->agent_id)->first()->notify(new PolicyAddNotification($sme_policy));
                }
                elseif($request->$policy_name == 'health'){
                    $redirect_url='update-policy/health-policy-update';

                    $name=HealthPolicy::where('id',decryptid($request->$id))->with('company')->first();
                    $url = $name->policy_copy;
                    $policy_copy_status=$name->policy_copy_status;
                    if($request->has('policy_number_'.$value)&&$request->has('policy_copy_'.$value)){
                        $url='policies/health/'.str_replace(" ","_",$name->company->name).'_'.$name->company->id.'/'.str_replace('/', '', $request->$policy_number).'.pdf';
                        if (Storage::disk('s3')->exists($url)) 
                            Storage::disk('s3')->delete($url);
                        Storage::disk('s3')->put($url,file_get_contents($request->$policy_copy));
                        $policy_copy_status=2;
                    }
                    $health_policy=HealthPolicy::updateOrCreate([
                        'id' => decryptid($request->$id),
                    ],["branch_imd_id"=>(int)$request->$branch_imd,
                        "policy_copy"=>$url, // check policy upload or not
                        "policy_number"=>$request->$policy_number,
                        "issue_date"=>$request->$issue_date,
                        "start_date"=>$request->$start_date,
                        "end_date"=>$request->$end_date,
                        "policy_tenure"=>$request->$policy_tenure,
                        'policy_copy_status' => $policy_copy_status,
                        "updated_by_id" => Auth::user()->id,
                    ]); 
                    $health_policy=$health_policy->toArray();
                    $health_policy['policy_type']='HEALTH';
                    $health_policy['notification_type']='book';
                    $health_policy['message']='Updated Health Policy('.$health_policy['inward_no'].') Recieved!';
                    Agent::where('id',$name->agent_id)->first()->notify(new PolicyAddNotification($health_policy));
                }
                elseif ($request->$policy_name == 'motor') {
                    $redirect_url='update-policy/motor-policy-update';
                    $tp_start_date='tp_start_date_'.$value;
                    $tp_end_date='tp_end_date_'.$value;
                    $name=MotorPolicy::where('id',decryptid($request->$id))->with('company')->first();
                    $url = $name->policy_copy;
                    $policy_copy_status=$name->policy_copy_status;
                    if($request->has('policy_number_'.$value)&&$request->has('policy_copy_'.$value)){
                        $url='policies/motor/'.str_replace(" ","_",$name->company->name).'_'.$name->company->id.'/'.str_replace('/', '', $request->$policy_number).'.pdf';
                        if (Storage::disk('s3')->exists($url)) 
                            Storage::disk('s3')->delete($url);
                        Storage::disk('s3')->put($url,file_get_contents($request->$policy_copy));
                        $policy_copy_status=2;
                    }
                    $motor_policy=MotorPolicy::updateOrCreate([
                        'id' => decryptid($request->$id),
                    ],["branch_imd_id"=>(int)$request->$branch_imd,
                        "policy_copy"=>$url, // check policy upload or not
                        "policy_number"=>$request->$policy_number,
                        "issue_date"=>$request->$issue_date,
                        "start_date"=>$request->$start_date,
                        "end_date"=>$request->$end_date,
                        "policy_tenure"=>$request->$policy_tenure,
                        'policy_copy_status' => $policy_copy_status,
                        "updated_by_id" => Auth::user()->id,
                    ]);  
                    MotorPolicyVehical::where('policy_id', decryptid($request->$id))->update(['tp_start_date' => $request->$tp_start_date,'tp_end_date' => $request->$tp_end_date]);
                    $motor_policy=$motor_policy->toArray();
                    $motor_policy['policy_type']='MOTOR';
                    $motor_policy['notification_type']='book';
                    $motor_policy['message']='Updated Motor Policy('.$motor_policy['inward_no'].') Recieved!';
                    Agent::where('id',$name->agent_id)->first()->notify(new PolicyAddNotification($motor_policy));
                }
            }

            if($request->module_name == 'all')
            {
                $redirect_url='update-policy/list-all-policy';
            }
            $response = [
                'status' => true,
                'message' => 'Policy Updated Successfully.',
                'icon' => 'success',
                'redirect_url' => $redirect_url,
            ];
        }catch(\Throwable $e){
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'redirect_url' => 'update-policy/list-all-policy',
                'icon' => 'error',
            ];
        }
        return response($response);
    }


    public function update_policy_export(Request $request) 
    {
        $array_gbr=[];
        $array_gbr= motopolicy_data($request)->latest()->get();
        $array_gbr= healthpolicy_data($request)->latest()->get(['id','product_id','customer_id','company_branch_id','agent_id','policy_number','inward_no','company_id','policy_tenure','branch_imd_id','issue_date','start_date','end_date','total_premium','od','policy_copy']); 
        $array_gbr= smepolicy_data($request)->latest()->get(['id','product_id','customer_id','company_branch_id','policy_number','agent_id','inward_no','company_id','policy_tenure','branch_imd_id','issue_date','start_date','end_date','total_premium','od','policy_copy']);

        
        if(sizeof($array_gbr) != 0 && $array_gbr->isNotEmpty() )
              {
            $myFile = Excel::raw(new ExportUpdateAllPolicy($request->data), 'Xlsx');
            $response = array(
                'status' => 'true',
                'name' => "PendingAllPolicy_data.xlsx",
                'file' => "data:application/vnd.ms-excel;base64," . base64_encode($myFile),
            );
        }
        else
        {
            $response = array(
                'status' => false,
                'icon' => 'error',
                'msg' => "No Data available",
            );

        }
        return $response;
    }
}
