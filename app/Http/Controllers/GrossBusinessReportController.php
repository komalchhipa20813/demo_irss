<?php

namespace App\Http\Controllers;

use App\Models\{Agent, Fdo,IrssBranch,Company,MotorPolicy,HealthPolicy,SmePolicy};
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GDRExport;
use Carbon\Carbon;
use DB;

class GrossBusinessReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $data['irss_branches'] = IrssBranch::where('status',1)->get(['id','name']);
       $data['companies'] = Company::where('status',1)->get(['id','name']);
       $data['agents'] = Agent::where('status',1)->get(['id','first_name','last_name','middle_name','prefix','code']);
       $data['fdos'] = Fdo::where('status',1)->get(['id','code','prefix','first_name','middle_name','last_name']);
       return view('pages.reports.gross-business.index',compact('data'));
    }

    public function export_gbr(Request $request)
    {
       try{
              $insurance=$request->insurance;
              $end_date=$request->end_date;
              $from_date=$request->from_date;
              $expiry_from_date=$request->expiry_from_date;
              $expiry_end_date=$request->expiry_end_date;
              $fdo=($request->fdo)?$request->fdo:'';
              $agent=($request->agent)?$request->agent:'';
              $company=($request->company)?$request->company:'';
              $company_branch_name=($request->company_branch_name)?$request->company_branch_name:'';
              $product=($request->product)?$request->product:'';
              $branch=($request->branch)?$request->branch:'';
              $date_formate=($request->date_format)?$request->date_format:'';

              $array_gbr=[];

              if($insurance == 'MOTOR')
              {
                     $array_gbr= $this->motopolicy_data($request);
              }
              elseif($insurance == 'HEALTH')
              {
                     $array_gbr= $this->healthpolicy_data($request); 
              }
              elseif($insurance == 'SME')
              {
                     $array_gbr= $this->smepolicy_data($request);
              }
              elseif($insurance == 'ALL')
              {
                     $motor_array_gbr= $this->motopolicy_data($request);
                     $health_array_gbr= $this->healthpolicy_data($request);
                     $sme_array_gbr= $this->smepolicy_data($request); 

                     if($motor_array_gbr->isNotEmpty())
                     {
                     $array_gbr= $motor_array_gbr;
                     }elseif($health_array_gbr->isNotEmpty())
                     {
                     $array_gbr= $health_array_gbr;
                     }elseif($sme_array_gbr->isNotEmpty())
                     {
                     $array_gbr= $sme_array_gbr;
                     }
                     else
                     {
                     $array_gbr=[];
                     }
              }
              if(!empty($insurance) && sizeof($array_gbr) != 0 && $array_gbr->isNotEmpty() )
              {
                     $myFile = Excel::raw(new GDRExport($insurance,$from_date, $end_date,$expiry_from_date,$expiry_end_date,$fdo,$agent,$company,$company_branch_name,$product,$branch, $date_formate), 'Xlsx');;
                     $response = array(
                            'data'=>$array_gbr,
                            'status' => true,
                            'type'=>'Excel',
                            'name' => "GB-Report.xlsx",
                            'file' => "data:application/vnd.ms-excel;base64," . base64_encode($myFile),
                     );
                     return response()->json($response);
              }else{
                     $response = array(
                            'status' => false,
                            'message' =>'No Data Avilable.',
                            'icon' => 'error',
                            'redirect_url' => "gross-business",
                     );
                     return response()->json($response);
              }   
              }catch(\Throwable $e){
                     dd($e);
                     $response = [
                         'status' => false,
                         'message' => 'Something Went Wrong! Please Try Again.',
                         'redirect_url' => 'health-policy',
                         'icon' => 'error',
                     ];
              }
        
       
    }

    public function motopolicy_data($request)
    {
        $motor_query= MotorPolicy::where('status','!=',2)->with('product','sub_product','customer','branch','company','companyBranch','branch_imd_name','payments','agent','motor_policy_vehicle');
        if(isset($request->from_date)&& isset($request->end_date))
        {
              $from_date = Carbon::createFromFormat('d-m-Y', $request->from_date)->format('Y-m-d 00:00:00');
              $to_date = Carbon::createFromFormat('d-m-Y',$request->end_date)->format('Y-m-d 23:59:59');
              $motor_query->whereBetween('created_at', [$from_date, $to_date]);
             }
              if(isset($request->expiry_from_date) && isset($request->expiry_end_date))
             {
                     $from_date = Carbon::parse($request->expiry_from_date)->format('Y-m-d 00:00:00');
                     $to_date = Carbon::parse($request->expiry_end_date)->format('Y-m-d 23:59:59');

                     $motor_query->whereBetween(DB::raw("STR_TO_DATE(end_date,'%d-%m-%Y')"), [$from_date, $to_date]);
             }

             if(isset($request->branch))
             {
                    $motor_query->where('irss_branch_id',$request->branch);
             }
             if(isset($request->company))
             {
                    $motor_query->where('company_id',$request->company);
             }
             if(isset($request->company_branch_name))
             {
                    $motor_query->where('company_branch_id',$request->company_branch_name);
             }
              if(isset($request->agent))
             {
                    $motor_query->where('agent_id',$request->agent);
             }

             if(isset($request->fdo)){
                    $motor_query->whereHas('agent', function ($query) use ($request) {
                        $query->where('fdo_id', $request->fdo);
                    });
              }
             $motorPolicys=$motor_query->latest()->get();

             return $motorPolicys;
    }

    public function healthpolicy_data($request)
    {
       $health_query= HealthPolicy::query()->where('status','!=',2)->with('product','sub_product','customer','branch','company_branch','company','branch_imd_name','payments','agent');
            

             if(isset($request->from_date)&& isset($request->end_date))
             {
              $from_date = Carbon::createFromFormat('d-m-Y', $request->from_date)->format('Y-m-d 00:00:00');
              $to_date = Carbon::createFromFormat('d-m-Y',$request->end_date)->format('Y-m-d 23:59:59');
              $health_query->whereBetween('created_at', [$from_date, $to_date]);
             }
             if(isset($request->expiry_from_date) && isset($request->expiry_end_date))
             {
              $from_date = Carbon::parse($request->expiry_from_date)->format('Y-m-d 00:00:00');
              $to_date = Carbon::parse($request->expiry_end_date)->format('Y-m-d 23:59:59');
              $health_query->whereBetween(DB::raw("STR_TO_DATE(end_date,'%d-%m-%Y')"), [$from_date, $to_date]);
             }
             if(isset($request->branch))
             {
                    $health_query->where('irss_branch_id',$request->branch);
             }
             if(isset($request->company))
             {
                    $health_query->where('company_id',$request->company);
             }
             if(isset($request->company_branch_name))
             {
                    $health_query->where('company_branch_id',$request->company_branch_name);
             }
              if(isset($request->agent))
             {
                    $health_query->where('agent_id',$request->agent);
             }

             if(isset($request->fdo)){
                    $health_query->whereHas('agent', function ($query) use ($request) {
                        $query->where('fdo_id', $request->fdo);
                    });
              }
             $healthPolicys=$health_query->latest()->get();
             return $healthPolicys;
    }

    public function smepolicy_data($request)
    {
        $sme_query= $sme_query= SmePolicy::where('status','!=',2)->with('product','sub_product','customer','branch','companyBranch','branch_imd_name','payments','agent','company');
            

             if(isset($request->from_date)&& isset($request->end_date))
             {
                     $from_date = Carbon::createFromFormat('d-m-Y', $request->from_date)->format('Y-m-d 00:00:00');
                     $to_date = Carbon::createFromFormat('d-m-Y',$request->end_date)->format('Y-m-d 23:59:59');
                     $sme_query->whereBetween('created_at', [$from_date, $to_date]);
             }
             if(isset($request->expiry_from_date) && isset($request->expiry_end_date))
             {
                     $from_date = Carbon::parse($request->expiry_from_date)->format('Y-m-d 00:00:00');
                     $to_date = Carbon::parse($request->expiry_end_date)->format('Y-m-d 23:59:59');
                     $sme_query->whereBetween(DB::raw("STR_TO_DATE(end_date,'%d-%m-%Y')"), [$from_date, $to_date]);
             }

             if(isset($request->branch))
             {
                    $sme_query->where('irss_branch_id',$request->branch);
             }
             if(isset($request->company))
             {
                    $sme_query->where('company_id',$request->company);
             }
             if(isset($request->company_branch_name))
             {
                    $sme_query->where('company_branch_id',$request->company_branch_name);
             }
              if(isset($request->agent))
             {
                    $sme_query->where('agent_id',$request->agent);
             }

             if(isset($request->fdo)){
                    $sme_query->whereHas('agent', function ($query) use ($request) {
                        $query->where('fdo_id', $request->fdo);
                    });
              }
             $smePolicys=$sme_query->latest()->get();
             return $smePolicys;
    }

    
}
