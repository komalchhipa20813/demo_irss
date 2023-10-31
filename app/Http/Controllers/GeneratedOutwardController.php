<?php

namespace App\Http\Controllers;

use App\Models\BusinessCategory;
use App\Models\Company;
use App\Models\GeneratedOutward;
use App\Models\HealthPolicy;
use App\Models\IrssBranch;
use App\Models\MotorPolicy;
use App\Models\SmePolicy;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GeneratedOutwardController extends Controller
{
    /* generated outward index */
    public function index()
    {
        $data['irss_branches'] = IrssBranch::where('status',1)->get(['id','name']);
        $data['companies'] = Company::where('status',1)->get(['id','name']);
        return view('pages.generated.index',compact('data'));
    }

    /* listing of generated outward */
    public function listing(Request $request)
    {
        $users = GeneratedOutward::query()->where('status','!=',2)->with('branch','company_branch','company','branch_imd_name');
        if($request->ajax()){
            if(isset($request->outward_status)){
                $users->where('generated_outward_status',$request->outward_status);
            }
            if(isset($request->branch)){
                $users->where('irss_branch_id',$request->branch);
            }
            if(isset($request->company)){
                $users->where('company_id',$request->company);
            }
            if(isset($request->company_branch_name)){
                $users->where('company_branch_id',$request->company_branch_name);
            }
            if(isset($request->branch_imd)){
                $users->where('branch_imd_id',$request->branch_imd);
            }
            if(isset($request->outward_no)){
                $users->where('outward_no',$request->outward_no);
            }
            if (isset($request->from_date) && isset($request->to_date)) {

                $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
                $to_date = Carbon::parse($request->to_date)->format('Y-m-d 23:59:59');
                $users->whereBetween('created_at', [$from_date, $to_date]);
            }else{
                if(isset($request->from_date)){
                    $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
                    $users->where('created_at',$from_date);
                }
                if(isset($request->to_date)){
                    $to_date = Carbon::parse($request->to_date)->format('Y-m-d 23:59:59');
                    $users->where('created_at',$to_date);
                }
            }
        }

        $users = $users->latest()->get();
        $records = [];
        if(isset($users) && !empty($users)){
            foreach ($users as $key => $row) {
                $document=$row->generated_outward_status==1?
                '<form id="Upload_copy_form'.$row->id.'" class="Upload_copy_form"><div class="mb-3">
                <input class="form-control " name="generated_outward_copy" type="file" id="generated_outward_copy'.$row->id.'" accept="application/pdf">
                <button  class="btn btn-primary upload" data-id="'.$row->id.'" id="">Upload</button>
                </div></form>':
                '<div class="btn btn-success"><a href="'.asset('/storage/reports/generated/').'/'.$row->outward_no.'.pdf" download><i class="mdi mdi-download"></i></a></div>';
                $records[] = array(
                    '0' => $key+1,
                    '1' => '<a href="'.asset('/storage/reports/generate/').'/'.$row->outward_no.'.pdf" download>'.$row->outward_no.'</a>',
                    '2' => $row->branch->name,
                    '3' => $row->company->name,
                    '4' => $row->company_branch->name,
                    '5' => $row->branch_imd_name->name,
                    '6' => $document,
                );
            }
        }

        return response(['data'=>$records]);
    }
    /* upload scanned pdf */
    public function upload(Request $request, $id)
    {
        try{
            $data=GeneratedOutward::find($id);
            if (isset($request->generated_outward_copy)) {
                $request->generated_outward_copy->move(public_path('storage/reports/generated/'), $data->outward_no.'.pdf');
            }
            $data->update(['generated_outward_status'=>2]);
            HealthPolicy::where('outward_id',$id)->update(['policy_status'=>3]);
            MotorPolicy::where('outward_id',$id)->update(['policy_status'=>3]);
            SmePolicy::where('outward_id',$id)->update(['policy_status'=>3]);
            $response = [
                'status' => true,
                'message' => 'Outward Uploaded Successfully.',
                'icon' => 'success',
            ];
        }catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' =>'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
        }
        return response($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
