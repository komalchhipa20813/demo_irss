<?php

namespace App\Http\Controllers;

use App\Exports\CancelHealthPolicyExport;
use App\Exports\CancelMotorPolicyExport;
use App\Exports\CancelSmePolicyExport;
use App\Models\Agent;
use App\Models\Company;
use App\Models\HealthPolicy;
use App\Models\IrssBranch;
use App\Models\MotorPolicy;
use App\Models\Product;
use App\Models\SmePolicy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CancelPolicyController extends Controller
{
    public function motorPolicyIndex()
    {
        $data['irss_branches'] = IrssBranch::where('status',1)->get(['id','name']);
        $data['companies'] = Company::where('status',1)->get(['id','name']);
        $data['products'] = Product::where('status',1)->where('policy_type',2)->get(['id','name']);
        $data['agents'] = Agent::where('status',1)->get(['id','first_name','last_name','middle_name','prefix','code']);
        return view('pages.reports.cancel-policy.motor-policy',compact('data'));
    }

     /* listing of motor policy */
    public function motorPolicyListing(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
        $totalRecords = MotorPolicy::where('status',3)->count();
        $motorPolicy = MotorPolicy::where('status',3)->with('customer','company','agent_only','motor_policy_vehicle_only','branch_imd_name');
        if($request->ajax()){
            if(isset($request->agent)){
                $motorPolicy->where('agent_id',$request->agent);
            }
            if(isset($request->name)){
                $motorPolicy->whereHas('customer', function ($query) use ($request) {
                    $query->where('first_name', 'like', '%'.$request->name.'%')
                    ->orWhere('middle_name', 'like', '%'.$request->name.'%')
                    ->orWhere('last_name', 'like', '%'.$request->name.'%');
            });
            }
            if(isset($request->branch)){
                $motorPolicy->where('irss_branch_id',$request->branch);
            }
            if(isset($request->company)){
                $motorPolicy->where('company_id',$request->company);
            }
            if(isset($request->cheque_no)){
                $motorPolicy->whereHas('payments', function ($query) use ($request) {
                    $query->where('payment_type', 2)->where('number', $request->cheque_no);
                });
            }
            if(isset($request->inward_no)){
                $motorPolicy->where('inward_no',$request->inward_no);
            }
            if(isset($request->policy_no)){
                $motorPolicy->where('policy_number',$request->policy_no);
            }
            if(isset($request->product)){
                $motorPolicy->whereHas('product', function ($query) use ($request) {
                    $query->where('id', $request->product);
                });
            }
            if(isset($request->policy_no)){
                $motorPolicy->where('policy_number',$request->policy_no);
            }
            if(isset($request->engine_no)){
                $motorPolicy->whereHas('motor_policy_vehicle', function ($query) use ($request) {
                    $query->where('engine_no', $request->engine_no);
                });

            }
            if(isset($request->chasis_no)){
                $motorPolicy->whereHas('motor_policy_vehicle', function ($query) use ($request) {
                    $query->where('chasiss_no', $request->chasis_no);
                });
            }
            if(isset($request->registration_no)){
                $motorPolicy->whereHas('motor_policy_vehicle', function ($query) use ($request) {
                    $query->where('registration_no', realVehicleNo($request->registration_no));
                });
            }
            if (isset($request->policy_start_date) && isset($request->policy_end_date)) {
                $motorPolicy->where('start_date', '<=', $request->policy_start_date)->where('end_date', '>=', $request->policy_end_date);
            }else{
                if(isset($request->policy_start_date)){
                    $motorPolicy->where('start_date',$request->policy_start_date);
                }
                if(isset($request->policy_end_date)){
                    $motorPolicy->where('end_date',$request->policy_end_date);
                }
            }

            if (isset($request->from_date) && isset($request->end_date)) {

                $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
                $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                $motorPolicy->whereBetween('created_at', [$from_date, $to_date]);
            }else{
                if(isset($request->from_date)){
                    $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
                    $motorPolicy->where('created_at',$from_date);
                }
                if(isset($request->end_date)){
                    $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                    $motorPolicy->where('created_at',$to_date);
                }
            }
        }
        $totalRecordswithFilter = $motorPolicy->count();
        $motorPolicy = $motorPolicy
        ->skip($start)
        ->take($rowperpage)
        ->get();
        $records = [];
        if(isset($motorPolicy) && !empty($motorPolicy)){
            foreach ($motorPolicy as $key => $row) {
                $records[] = array(
                    '0' => $row->policy_number,
                    '1'=>!empty($row->motor_policy_vehicle_only)?vehicleNO($row->motor_policy_vehicle_only->registration_no):'',
                    '2' => $row->customer->first_name.' '.$row->customer->middle_name.' '.$row->customer->last_name,
                    '3'=>$row->inward_no,
                    '4'=>!empty($row->company)?getFirstString($row->company->name):'',
                    '5'=>$row->policy_cancel_reason,
                    '6'=>$row->policy_cancel_remark,
                );
            }
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $records
        );
        return response($response);
    }

    //Health Policy
    public function healthPolicyIndex()
    {

    	$data['irss_branches'] = IrssBranch::where('status',1)->get(['id','name']);
        $data['companies'] = Company::where('status',1)->get(['id','name']);
        $data['products'] = Product::where('status',1)->where('policy_type',2)->get(['id','name']);
        $data['agents'] = Agent::where('status',1)->get(['id','first_name','last_name','middle_name','prefix','code']);
         return view('pages.reports.cancel-policy.health-policy',compact('data'));
    }

    /* listing of health policy */
    public function healthPolicyListing(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
        $totalRecords = HealthPolicy::where('status',3)->count();
        $users = HealthPolicy::where('status',3)->with('customer','company','agent_only','branch_imd_name');
        if($request->ajax()){
            if(isset($request->agent)){
                $users->where('agent_id',$request->agent);
            }
            if(isset($request->name)){
                $users->whereHas('customer', function ($query) use ($request) {
                    $query->where('first_name', 'like', '%'.$request->name.'%')
                    ->orWhere('middle_name', 'like', '%'.$request->name.'%')
                    ->orWhere('last_name', 'like', '%'.$request->name.'%');
                   });
            }
            if(isset($request->branch)){
                $users->where('irss_branch_id',$request->branch);
            }
            if(isset($request->company)){
                $users->where('company_id',$request->company);
            }
            if(isset($request->cheque_no)){
                $users->whereHas('health_policy_payments', function ($query) use ($request) {
                    $query->where('payment_type', 2)->where('number', $request->cheque_no);
                });
            }
            if(isset($request->inward_no)){
                $users->where('inward_no',$request->inward_no);
            }
            if(isset($request->policy_no)){
                $users->where('policy_number',$request->policy_no);
            }
            if(isset($request->product)){
                $users->whereHas('product', function ($query) use ($request) {
                    $query->where('id', $request->product);
                });
            }

            if (isset($request->policy_start_date) && isset($request->policy_end_date)) {
                $users->where('start_date', '<=', $request->policy_start_date)->where('end_date', '>=', $request->policy_end_date);
            }else{
                if(isset($request->policy_start_date)){
                    $users->where('start_date',$request->policy_start_date);
                }
                if(isset($request->policy_end_date)){
                    $users->where('end_date',$request->policy_end_date);
                }
            }

            if (isset($request->from_date) && isset($request->end_date)) {

                $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
                $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                $users->whereBetween('created_at', [$from_date, $to_date]);
            }else{
                if(isset($request->from_date)){
                    $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
                    $users->where('created_at',$from_date);
                }
                if(isset($request->end_date)){
                    $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                    $users->where('created_at',$to_date);
                }
            }
        }

        $totalRecordswithFilter = $users->count();
        $users = $users
        ->skip($start)
        ->take($rowperpage)
        ->get(['id','customer_id','policy_number','inward_no','policy_status','company_id','policy_copy_status','policy_copy','status','branch_imd_id','policy_cancel_reason','policy_cancel_remark']);
        $records = [];
        if(isset($users) && !empty($users)){
            foreach ($users as $key => $row) {
                $records[] = array(
                    '0' => $row->policy_number,
                    '3' => $row->customer->first_name.' '.$row->customer->middle_name.' '.$row->customer->last_name,
                    '1' => $row->inward_no,
                    '2' => $row->company->name,
                    '4'=>$row->policy_cancel_reason,
                    '5'=>$row->policy_cancel_remark,
                );
            }
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $records
        );
        return response($response);
    }

    //SME policy
    //
    public function smePolicyIndex()
    { 

    	$data['irss_branches'] = IrssBranch::where('status',1)->get(['id','name']);
        $data['companies'] = Company::where('status',1)->get(['id','name']);
        $data['products'] = Product::where('status',1)->where('policy_type',2)->get(['id','name']);
        $data['agents'] = Agent::where('status',1)->get(['id','first_name','last_name','middle_name','prefix','code']);
        return view('pages.reports.cancel-policy.sme-policy',compact('data'));
    }

     /* listing of sme policy */
    public function smePolicyListing(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
        $totalRecords = SmePolicy::where('status',3)->count();
        $sme_policies = SmePolicy::where('status',3)->with('customer','agent','company','branch_imd_name');
        if($request->ajax()){
            if(isset($request->agent)){
                $sme_policies->where('agent_id',$request->agent);
            }
            if(isset($request->name)){
                $sme_policies->whereHas('customer', function ($query) use ($request) {
                    $query->where('first_name', 'like', '%'.$request->name.'%')
                    ->orWhere('middle_name', 'like', '%'.$request->name.'%')
                    ->orWhere('last_name', 'like', '%'.$request->name.'%');
                   });
            }
            if(isset($request->branch)){
                $sme_policies->where('irss_branch_id',$request->branch);
            }
            if(isset($request->company)){
                $sme_policies->where('company_id',$request->company);
            }
            if(isset($request->cheque_no)){
                $sme_policies->whereHas('payments', function ($query) use ($request) {
                    $query->where('payment_type', 2)->where('number', $request->cheque_no);
                });
            }
            if(isset($request->inward_no)){
                $sme_policies->where('inward_no',$request->inward_no);
            }
            if(isset($request->policy_no)){
                $sme_policies->where('policy_number',$request->policy_no);
            }
            if(isset($request->product)){
                $sme_policies->whereHas('product', function ($query) use ($request) {
                    $query->where('id', $request->product);
                });
            }

            if (isset($request->policy_start_date) && isset($request->policy_end_date)) {
                $sme_policies->where('start_date', '<=', $request->policy_start_date)->where('end_date', '>=', $request->policy_end_date);
            }else{
                if(isset($request->policy_start_date)){
                    $sme_policies->where('start_date',$request->policy_start_date);
                }
                if(isset($request->policy_end_date)){
                    $sme_policies->where('end_date',$request->policy_end_date);
                }
            }

            if (isset($request->from_date) && isset($request->end_date)) {

                $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
                $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                $sme_policies->whereBetween('created_at', [$from_date, $to_date]);
            }else{
                if(isset($request->from_date)){
                    $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
                    $sme_policies->where('created_at',$from_date);
                }
                if(isset($request->end_date)){
                    $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                    $sme_policies->where('created_at',$to_date);
                }
            }
        }
        $totalRecordswithFilter = $sme_policies->count();
        $sme_policies = $sme_policies
        ->skip($start)
        ->take($rowperpage)
        ->get(['id','customer_id','inward_no','policy_status','agent_id','company_id','policy_number','policy_copy_status','status','branch_imd_id','policy_cancel_reason','policy_cancel_remark']); 
        $records = [];
        if(isset($sme_policies) && !empty($sme_policies)){
            foreach ($sme_policies as $key => $row) {
                $records[] = array(
                    '0' => $row->policy_number,
                    '3' => $row->customer->first_name.' '.$row->customer->middle_name.' '.$row->customer->last_name,
                    '1' => $row->inward_no,
                    '2' => $row->company->name,
                    '4'=>$row->policy_cancel_reason,
                    '5'=>$row->policy_cancel_remark,
                );
            }
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $records
        );
        return response($response);
    }

    public function export_motor_policy(Request $request)
    {
        $motorPolicy = MotorPolicy::where('status',3);
        
        if($request->ajax()){
            if(isset($request->agent)){
                $motorPolicy->where('agent_id',$request->agent);
            }
            if(isset($request->name)){
                $motorPolicy->whereHas('customer', function ($query) use ($request) {
                    $query->where('first_name', 'like', '%'.$request->name.'%')
                    ->orWhere('middle_name', 'like', '%'.$request->name.'%')
                    ->orWhere('last_name', 'like', '%'.$request->name.'%');
            });
            }
            if(isset($request->branch)){
                $motorPolicy->where('irss_branch_id',$request->branch);
            }
            if(isset($request->company)){
                $motorPolicy->where('company_id',$request->company);
            }
            if(isset($request->cheque_no)){
                $motorPolicy->whereHas('payments', function ($query) use ($request) {
                    $query->where('payment_type', 2)->where('number', $request->cheque_no);
                });
            }
            if(isset($request->inward_no)){
                $motorPolicy->where('inward_no',$request->inward_no);
            }
            if(isset($request->policy_no)){
                $motorPolicy->where('policy_number',$request->policy_no);
            }
            if(isset($request->product)){
                $motorPolicy->whereHas('product', function ($query) use ($request) {
                    $query->where('id', $request->product);
                });
            }
            if(isset($request->policy_no)){
                $motorPolicy->where('policy_number',$request->policy_no);
            }
            if(isset($request->engine_no)){
                $motorPolicy->whereHas('motor_policy_vehicle', function ($query) use ($request) {
                    $query->where('engine_no', $request->engine_no);
                });

            }
            if(isset($request->chasis_no)){
                $motorPolicy->whereHas('motor_policy_vehicle', function ($query) use ($request) {
                    $query->where('chasiss_no', $request->chasis_no);
                });
            }
            if(isset($request->registration_no)){
                $motorPolicy->whereHas('motor_policy_vehicle', function ($query) use ($request) {
                    $query->where('registration_no', realVehicleNo($request->registration_no));
                });
            }
            if (isset($request->policy_start_date) && isset($request->policy_end_date)) {
                $motorPolicy->where('start_date', '<=', $request->policy_start_date)->where('end_date', '>=', $request->policy_end_date);
            }else{
                if(isset($request->policy_start_date)){
                    $motorPolicy->where('start_date',$request->policy_start_date);
                }
                if(isset($request->policy_end_date)){
                    $motorPolicy->where('end_date',$request->policy_end_date);
                }
            }

            if (isset($request->from_date) && isset($request->end_date)) {

                $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
                $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                $motorPolicy->whereBetween('created_at', [$from_date, $to_date]);
            }else{
                if(isset($request->from_date)){
                    $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
                    $motorPolicy->where('created_at',$from_date);
                }
                if(isset($request->end_date)){
                    $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                    $motorPolicy->where('created_at',$to_date);
                }
            }
        }
        $motorPolicy = $motorPolicy->get(['id']);

        if(!empty($motorPolicy) && sizeof($motorPolicy) != 0){

            // $response =[];
            $myFile = Excel::raw(new CancelMotorPolicyExport($request->data), 'Xlsx');
            $response = array(
                'status' => 'true',
                'name' => "CancelMotorPolicies.xlsx",
                'file' => "data:application/vnd.ms-excel;base64," . base64_encode($myFile),
            );

        }else{
            $response = array(
                'status' => false,
                'icon' => 'error',
                'msg' => "No Data available",
            );
        }

        return $response;

    }

     public function export_health_policy(Request $request) 
    {

        $users = HealthPolicy::query()->where('status',3);
        if($request->ajax()){
            if(isset($request->agent)){
                $users->where('agent_id',$request->agent);
            }
            if(isset($request->name)){
                $users->whereHas('customer', function ($query) use ($request) {
                    $query->where('first_name', 'like', '%'.$request->name.'%')
                    ->orWhere('middle_name', 'like', '%'.$request->name.'%')
                    ->orWhere('last_name', 'like', '%'.$request->name.'%');
                   });
            }
            if(isset($request->branch)){
                $users->where('irss_branch_id',$request->branch);
            }
            if(isset($request->company)){
                $users->whereHas('company_branch', function ($query) use ($request) {
                    $query->whereHas('company', function ($queryData) use ($request) {
                        $queryData->where('id',$request->company);
                    });
                });
            }
            if(isset($request->cheque_no)){
                $users->whereHas('health_policy_payments', function ($query) use ($request) {
                    $query->where('payment_type', 2)->where('number', $request->cheque_no);
                });
            }
            if(isset($request->inward_no)){
                $users->where('inward_no',$request->inward_no);
            }
            if(isset($request->policy_no)){
                $users->where('policy_number',$request->policy_no);
            }
            if(isset($request->product)){
                $users->whereHas('product', function ($query) use ($request) {
                    $query->where('id', $request->product);
                });
            }

            if (isset($request->policy_start_date) && isset($request->policy_end_date)) {
                $users->where('start_date', '<=', $request->policy_start_date)->where('end_date', '>=', $request->policy_end_date);
            }else{
                if(isset($request->policy_start_date)){
                    $users->where('start_date',$request->policy_start_date);
                }
                if(isset($request->policy_end_date)){
                    $users->where('end_date',$request->policy_end_date);
                }
            }

            if (isset($request->from_date) && isset($request->end_date)) {

                $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
                $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                $users->whereBetween('created_at', [$from_date, $to_date]);
            }else{
                if(isset($request->from_date)){
                    $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
                    $users->where('created_at',$from_date);
                }
                if(isset($request->end_date)){
                    $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                    $users->where('created_at',$to_date);
                }
            }
        }

        $users = $users->latest()->get(['id']);

        if(!empty($users) && sizeof($users) != 0){
            $myFile = Excel::raw(new CancelHealthPolicyExport($request->data), 'Xlsx');
            $response = array(
                'status' => 'true',
                'name' => "CancelHealthPolicies.xlsx",
                'file' => "data:application/vnd.ms-excel;base64," . base64_encode($myFile),
            );
        }else{
            $response = array(
                'status' => false,
                'icon' => 'error',
                'msg' => "No Data available",
            );

        }
        return $response;
    }

    public function export_sme_policy(Request $request) 
    {
        $sme_policies = SmePolicy::where('status',3);
        if($request->ajax()){
            if(isset($request->agent)){
                $sme_policies->where('agent_id',$request->agent);
            }
            if(isset($request->name)){
                $sme_policies->whereHas('customer', function ($query) use ($request) {
                    $query->where('first_name', 'like', '%'.$request->name.'%')
                    ->orWhere('middle_name', 'like', '%'.$request->name.'%')
                    ->orWhere('last_name', 'like', '%'.$request->name.'%');
                   });
            }
            if(isset($request->branch)){
                $sme_policies->where('irss_branch_id',$request->branch);
            }
            if(isset($request->company)){
                $sme_policies->whereHas('company_branch', function ($query) use ($request) {
                    $query->whereHas('company', function ($queryData) use ($request) {
                        $queryData->where('id',$request->company);
                    });
                });
            }
            if(isset($request->cheque_no)){
                $sme_policies->whereHas('payments', function ($query) use ($request) {
                    $query->where('payment_type', 2)->where('number', $request->cheque_no);
                });
            }
            if(isset($request->inward_no)){
                $sme_policies->where('inward_no',$request->inward_no);
            }
            if(isset($request->policy_no)){
                $sme_policies->where('policy_number',$request->policy_no);
            }
            if(isset($request->product)){
                $sme_policies->whereHas('product', function ($query) use ($request) {
                    $query->where('id', $request->product);
                });
            }

            if (isset($request->policy_start_date) && isset($request->policy_end_date)) {
                $sme_policies->where('start_date', '<=', $request->policy_start_date)->where('end_date', '>=', $request->policy_end_date);
            }else{
                if(isset($request->policy_start_date)){
                    $sme_policies->where('start_date',$request->policy_start_date);
                }
                if(isset($request->policy_end_date)){
                    $sme_policies->where('end_date',$request->policy_end_date);
                }
            }

            if (isset($request->from_date) && isset($request->end_date)) {

                $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
                $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                $sme_policies->whereBetween('created_at', [$from_date, $to_date]);
            }else{
                if(isset($request->from_date)){
                    $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
                    $sme_policies->where('created_at',$from_date);
                }
                if(isset($request->end_date)){
                    $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                    $sme_policies->where('created_at',$to_date);
                }
            }
        }
        $sme_policies = $sme_policies->latest()->get(['id']);

        if(!empty($sme_policies) && sizeof($sme_policies) != 0){
            $myFile = Excel::raw(new CancelSmePolicyExport($request->data), 'Xlsx');
            $response = array(
                'status' => 'true',
                'name' => "CancelSmePolicies.xlsx",
                'file' => "data:application/vnd.ms-excel;base64," . base64_encode($myFile),
            );
        }else{
            $response = array(
                'status' => false,
                'icon' => 'error',
                'msg' => "No Data available",
            );

        }
        return $response;
    }
}
