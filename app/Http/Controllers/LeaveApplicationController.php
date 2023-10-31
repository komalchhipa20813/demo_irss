<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{User,LeaveApplication};
class LeaveApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.settings.leave-application.index');
    }

     //Listing Data Of Leave
    public function listing(Request $request){

        $query=LeaveApplication::latest()->with('user');

        if($request->status && !empty($request->status) && $request->status != '0')
        {
            $query->where('status',$request->status);
        }

        if(Auth::user()->role_id != 1)
        {
            $query->where('user_id',Auth::user()->id);
        }

        $data['leaveData']= $query->get();
        $result = [];
        
        foreach ($data['leaveData'] as $key=> $leave) {
            $button = '';
            $status='';
            if($leave->status == 1)
            {
               if(in_array("70", permission())){

                $button .= '<button class="edit_leave btn btn-sm btn-success m-1"  data-id="'.encryptid($leave['id']).'">
                <i class="mdi mdi-square-edit-outline"></i>
                </button>';

                $button .= '<a class="btn btn-danger btn-lg " id="'.encryptid($leave['id']) .'"   role="button" onclick="rollback_status(this);" aria-disabled="true">Cancel</a>';
                }  
            }
            elseif($leave->status == 4)
            {
                $button .='<a href="#" class="btn btn-dark btn-lg disabled" role="button" aria-disabled="true">Rollback</a>';
                $status='<a href="#" class="btn btn-dark btn-lg disabled" role="button" aria-disabled="true">Canceled</a>';
            }
            elseif($leave->status == 2){
                $button .='<a href="#" class="btn btn-success btn-lg disabled" role="button" aria-disabled="true">Approved</a>';
                $status='<a href="#" class="btn btn-success btn-lg disabled" role="button" aria-disabled="true">Approved</a>';
            }
            elseif($leave->status == 3){
                $button .='<a href="#" class="btn btn-danger btn-lg disabled" role="button" aria-disabled="true">NotApproved</a>';
                $status='<a href="#" class="btn btn-danger btn-lg disabled" role="button" aria-disabled="true">NotApproved</a>';
            }
            

            
            if($leave->status == 1)
            {
                $status .='<a class="btn btn-success status btn-lg " role="button" aria-disabled="true" data-status="2" data-id="'.encryptid($leave['id']) .'" >Approve</a>';
                 $status .=' <a class="btn btn-danger status btn-lg " role="button" aria-disabled="true" data-id="'.encryptid($leave['id']) .'"  data-status="3">Not Approve</a>';
            }
           
            $result[] = array(
            "from_to_date"=>date('d-m-Y',strtotime($leave->from_date)) .' To '.date('d-m-Y',strtotime($leave->to_date)),
            "type"=>ucwords($leave->leave_type),
            "reason"=>$leave->leave_reason,
            "user_name"=>(!empty($leave['user']['prefix'])) ? ucwords($leave['user']['prefix'].'. '.$leave['user']['first_name'].' '.$leave['user']['middle_name'].' '.$leave['user']['last_name']) :ucwords($leave['user']['first_name'].' '.$leave['user']['middle_name'].' '.$leave['user']['last_name']) ,  
            "child_status"=>'',
            "parent_status"=>'',
            "editORrollback"=>$button,
            "status"=> $status,
            );
        }
        $dataset =array('data'=>$result);
        return json_encode($dataset);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users= User::where('status','!=',2)->where('id','!=',1)->orderBy('first_name','ASC')->get(['id','prefix','first_name','middle_name','last_name'])->toArray();

        return view('pages.settings.leave-application.leave',compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $request->validate(
                [
                    'leave_type' => 'required',
                    'from_date' => 'required',
                    'to_date' => 'required',
                    'leave_type_day' => 'required',
                    'leave_reason' => 'required',

                ]
            );
            
            LeaveApplication::updateOrCreate(
                [
                    'id' => decryptid($request->id),
                ],
                [
                    'user_id'=>Auth::user()->id,
                    'leave_type' => $request->leave_type,
                    'from_date' => $request->from_date,
                    'to_date' => $request->to_date,
                    'leave_type_day' => $request->leave_type_day,
                    'work_handover_user_id'=>$request->work_handover_user_id,
                    'leave_reason' => $request->leave_reason,
                    'status'=>1
                ]
                
            );
            $response = [
                'status' => true,
                'message' => 'Leave ' . (decryptid($request->id) == '0' ? 'Apply' : 'Updated') . ' Successfully',
                'icon' => 'success',
                'redirect_url' => "leave-application",
            ];
        }catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' =>'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
                'redirect_url' => "leave-application",
            ];
        }

        return response($response);
    }


    /* change status */
    public function ajaxchangestatus(Request $request) {
        $leave = LeaveApplication::where('id', decryptid($request->id))->first();
        if (is_null($leave)) {
            $response = [
                'status' => false,
                'message' => "Record not found",
                'icon' => 'error',
            ];
        } else {
            $update['status'] = $request->rollback;
            $result = LeaveApplication::where('id', decryptid($request->id))->update($update);
            if($request->rollback == 4)
            {
                $status_text='Cancel';
            }
            elseif($request->rollback == 3)
            {
                $status_text='Not Approved';
            }
            else
            {
                $status_text='Approved';
            }

            if ($result) {
                $response = [
                    'status' => true,
                    'message' => 'Leave '. $status_text .' Successfully',
                    'icon' => 'success',
                ];
            } else {
                $response = [
                    'status' => false,
                    'message' => "error in updating",
                    'icon' => 'error',
                ];
            }
        }
        return response($response);
    }


   

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         try {
            $leave = LeaveApplication::where('id',decryptid($id))->first();
            if(!is_null($leave) ){
                $data=[
                    'leave'=>$leave,
                    'from_date'=>date('Y-m-d',strtotime($leave['from_date'])),
                    'to_date'=>date('Y-m-d',strtotime($leave['to_date'])),
                ];
                $response = [
                    'data'=>$data,
                    'status'=>true,
                ];
            }
        }catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => "Something Went Wrong! Please Try Again.",
                'icon' => 'error',
            ];
        }
        return response($response);
    }

   

}
