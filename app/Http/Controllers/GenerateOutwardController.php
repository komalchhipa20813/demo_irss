<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\BranchImdName;
use App\Models\Company;
use App\Models\CompanyBranch;
use App\Models\GeneratedOutward;
use App\Models\HealthPolicy;
use App\Models\IrssBranch;
use App\Models\MotorPolicy;
use App\Models\Product;
use App\Models\Settings;
use App\Models\SmePolicy;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GenerateOutwardController extends Controller
{
    /* dashboard of generate outward */
    public function index()
    {
        $data['irss_branches'] = IrssBranch::where('status',1)->get(['id','name']);
        $data['companies'] = Company::where('status',1)->get(['id','name']);
        return view('pages.reports.generate.index',compact('data'));
    }

    /* listing of all pending policy */
    public function listing(Request $request)
    {
        $request->validate(
            [
                'branch' => 'required',
                'company' => 'required',
                'company_branch_name' => 'required',
                'branch_imd' => 'required',
                'from_date' => 'required',
                'to_date' => 'required',
            ]
        );
        $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
        $to_date = Carbon::parse($request->to_date)->format('Y-m-d 23:59:59');
        $motorPolicy = MotorPolicy::where('status',1)->where('policy_status',1)
            ->where('irss_branch_id',$request->branch)
            ->where('company_id',$request->company)
            ->where('company_branch_id',$request->company_branch_name)
            ->where('branch_imd_id',$request->branch_imd)
            ->whereBetween('created_at', [$from_date, $to_date])->with('customer','branch','company')->latest()->get(['customer_id','irss_branch_id','company_id','id','inward_no','created_at']);
        $healthPolicy = HealthPolicy::where('status',1)->where('policy_status',1)
            ->where('irss_branch_id',$request->branch)
            ->where('company_id',$request->company)
            ->where('company_branch_id',$request->company_branch_name)
            ->where('branch_imd_id',$request->branch_imd)
            ->whereBetween('created_at', [$from_date, $to_date])->with('customer','branch','company')->latest()->get(['customer_id','irss_branch_id','company_id','id','inward_no','created_at']);
        $smePolicy = SmePolicy::where('status',1)->where('policy_status',1)
            ->where('irss_branch_id',$request->branch)
            ->where('company_id',$request->company)
            ->where('company_branch_id',$request->company_branch_name)
            ->where('branch_imd_id',$request->branch_imd)
            ->whereBetween('created_at', [$from_date, $to_date])->with('customer','branch','company')->latest()->get(['customer_id','irss_branch_id','company_id','id','inward_no','created_at']);
        $records = [];
        $i=0;
        if(isset($motorPolicy) && !empty($motorPolicy)){
            foreach ($motorPolicy as $key => $row) {
                $records[] = array(
                    '0' => '<input type="checkbox" class="checkbox"  name="id[]" id="' . encryptid($row->id) . '" data-id="'.$row->inward_no.'" s onclick="single_unselected(this);"   style="    margin-left: 8px;"/>',
                    '1' => ++$i,
                    '2' => $row->inward_no,
                    '3' => $row->customer->first_name.' '.$row->customer->middle_name.' '.$row->customer->last_name,
                    '4' => 'MOTOR',
                    '5' => $row->company->name,
                    '6' => $row->branch->name,
                    '7'=>Carbon::parse($row->created_at)->format('d-M-Y'),
                );
            }
        }
        if(isset($healthPolicy) && !empty($healthPolicy)){
            foreach ($healthPolicy as $key => $row) {
                $records[] = array(
                    '0' => '<input type="checkbox" class="checkbox"  name="id[]" id="' . encryptid($row->id) . '" data-id="'.$row->inward_no.'" s onclick="single_unselected(this);"   style="    margin-left: 8px;"/>',
                    '1' => ++$i,
                    '2' => $row->inward_no,
                    '3' => $row->customer->first_name.' '.$row->customer->middle_name.' '.$row->customer->last_name,
                    '4' =>'HEALTH',
                    '5' => $row->company->name,
                    '6' => $row->branch->name,
                    '7'=>Carbon::parse($row->created_at)->format('d-M-Y'),
                );
            }
        }
        if(isset($smePolicy) && !empty($smePolicy)){
            foreach ($smePolicy as $key => $row) {
                $records[] = array(
                    '0' => '<input type="checkbox" class="checkbox"  name="id[]" id="' . encryptid($row->id) . '" data-id="'.$row->inward_no.'" s onclick="single_unselected(this);"   style="    margin-left: 8px;"/>',
                    '1' => ++$i,
                    '2' => $row->inward_no,
                    '3' => $row->customer->first_name.' '.$row->customer->middle_name.' '.$row->customer->last_name,
                    '4' => 'SME',
                    '5' => $row->company->name,
                    '6' => $row->branch->name,
                    '7'=>Carbon::parse($row->created_at)->format('d-M-Y'),
                );
            }
        }

        return response(['data'=>$records]);
    }
    /* fetch data for pdf and generate pdf */
    public function pdf(Request $request)
    {
        try{
            $data['comman_data']=$request->comman_data;
            $generate_name=Settings::where('name','generate_pdf_count')->where('key',Carbon::now()->format('dMY'))->first();
            if(empty($generate_name)){
                $generate_name=Settings::where('name','generate_pdf_count');
                $generate_name->update(['key'=>Carbon::now()->format('dMY'),'value'=>'RS1']);
                $generate_name=$generate_name->first();
            }
            $data['title']=$generate_name->key.'-'.$generate_name->value;
            $generate_name->update(['value'=>++$generate_name->value]);
            $outwardData=[
                'irss_branch_id' => $data['comman_data']['branch'],
                'company_id' => $data['comman_data']['company'],
                'company_branch_id' => $data['comman_data']['company_branch_name'],
                'branch_imd_id' => $data['comman_data']['branch_imd'],
                'outward_no' => $data['title'],
            ];
            $data['irss_branch_id'] = IrssBranch::find($data['comman_data']['branch'])->name;
            $data['company_id'] = Company::find($data['comman_data']['company'])->name;
            $data['company_branch_id'] = CompanyBranch::find($data['comman_data']['company_branch_name'])->name;
            $data['branch_imd_id'] = BranchImdName::find($data['comman_data']['branch_imd'])->name;
            $data['outward_no'] = $data['title'];
            $generated_outward=GeneratedOutward::create($outwardData);

            $motor_policies=MotorPolicy::whereIn('inward_no',$request->inward_no)->with('agent','customer','payments','motor_policy_vehicle');
            $motor_policies->update(['outward_id' => $generated_outward->id,'policy_status'=>2]);
            $motor_policies=$motor_policies->get(['agent_id','customer_id','id','total_premium','policy_number','created_at','inward_no',DB::raw("'MOTOR' as product"),'remark']);

            $health_policies=HealthPolicy::whereIn('inward_no',$request->inward_no)->with('agent','customer','payments');
            $health_policies->update(['outward_id' => $generated_outward->id,'policy_status'=>2]);
            $health_policies=$health_policies->get(['agent_id','customer_id','id','total_premium','policy_number','created_at','inward_no',DB::raw("'HEALTH' as product"),'remark']);

            $sme_policies=SmePolicy::whereIn('inward_no',$request->inward_no)->with('agent','customer','payments');
            $sme_policies->update(['outward_id' => $generated_outward->id,'policy_status'=>2]);
            $sme_policies=$sme_policies->get(['agent_id','customer_id','id','total_premium','policy_number','created_at','inward_no',DB::raw("'SME' as product"),'remark']);

            $data['policies'][0]=[];
            $data['policies'][1]=[];
            $data['policies'][2]=[];
            $data['policies'][3]=[];
            $data['policies'][4]=[];
            $data['policies'][5]=[];
            $data['policies'][6]=[];
            $data['policies'][7]=[];
            $data['policies'][8]=[];
            $data['policies'][9]=[];
            foreach($motor_policies as $policy){
                $type=$policy->payments[0]->payment_type;
                switch ($type) {
                    case "1":
                        array_push($data['policies'][0],$policy);
                        break;
                    case "2":
                        //Cheque
                        array_push($data['policies'][1],$policy);
                        break;
                    case "3":
                        // Demand Draft
                        array_push($data['policies'][2],$policy);
                        break;
                    case "4":
                        //Online Transaction
                        array_push($data['policies'][3],$policy);
                        break;
                    case "5":
                        // Cash & Cheque
                        array_push($data['policies'][4],$policy);
                        break;
                    case "6":
                        //  Cheque & Demand Draft
                        array_push($data['policies'][5],$policy);
                        break;
                    case "7":
                        //  Cash & Demand Draft
                        array_push($data['policies'][6],$policy);
                        break;
                    case "8":
                        //  Cash & Online payment
                        array_push($data['policies'][7],$policy);
                        break;
                    case "9":
                        //   Online payment & Cheque
                        array_push($data['policies'][8],$policy);
                        break;
                    case "10":
                        //   Online payment & Demand Draft
                        array_push($data['policies'][9],$policy);
                        break;
                    default:
                    return false;
                }
            }
            foreach($health_policies as $policy){
                $type=$policy->payments[0]->payment_type;
                switch ($type) {
                    case "1":
                        array_push($data['policies'][0],$policy);
                        break;
                    case "2":
                        //Cheque
                        array_push($data['policies'][1],$policy);
                        break;
                    case "3":
                        // Demand Draft
                        array_push($data['policies'][2],$policy);
                        break;
                    case "4":
                        //Online Transaction
                        array_push($data['policies'][3],$policy);
                        break;
                    case "5":
                        // Cash & Cheque
                        array_push($data['policies'][4],$policy);
                        break;
                    case "6":
                        //  Cheque & Demand Draft
                        array_push($data['policies'][5],$policy);
                        break;
                    case "7":
                        //  Cash & Demand Draft
                        array_push($data['policies'][6],$policy);
                        break;
                    case "8":
                        //  Cash & Online payment
                        array_push($data['policies'][7],$policy);
                        break;
                    case "9":
                        //   Online payment & Cheque
                        array_push($data['policies'][8],$policy);
                        break;
                    case "10":
                        //   Online payment & Demand Draft
                        array_push($data['policies'][9],$policy);
                        break;
                    default:
                    return false;
                }
            }
            foreach($sme_policies as $policy){
                $type=$policy->payments[0]->payment_type;
                switch ($type) {
                    case "1":
                        array_push($data['policies'][0],$policy);
                        break;
                    case "2":
                        //Cheque
                        array_push($data['policies'][1],$policy);
                        break;
                    case "3":
                        // Demand Draft
                        array_push($data['policies'][2],$policy);
                        break;
                    case "4":
                        //Online Transaction
                        array_push($data['policies'][3],$policy);
                        break;
                    case "5":
                        // Cash & Cheque
                        array_push($data['policies'][4],$policy);
                        break;
                    case "6":
                        //  Cheque & Demand Draft
                        array_push($data['policies'][5],$policy);
                        break;
                    case "7":
                        //  Cash & Demand Draft
                        array_push($data['policies'][6],$policy);
                        break;
                    case "8":
                        //  Cash & Online payment
                        array_push($data['policies'][7],$policy);
                        break;
                    case "9":
                        //   Online payment & Cheque
                        array_push($data['policies'][8],$policy);
                        break;
                    case "10":
                        //   Online payment & Demand Draft
                        array_push($data['policies'][9],$policy);
                        break;
                    default:
                    return false;
                }
            }
            $pdf = FacadePdf::loadView('pages.reports.generate.pdf',compact('data'))->setPaper('a4', 'landscape');
            $path = public_path().'/storage/reports/generate/';
            if(!is_dir($path)){
                mkdir($path);
            }
            $fileName =  $data['title']. '.pdf' ;
            $pdf->save($path . $fileName);
            $pdf = public_path().'/storage/reports/generate/'.$fileName;
            return response()->download($pdf, $fileName, ['x-filename' => $fileName]);
        }catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' =>'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
            return response($response);
        }
    }
}
