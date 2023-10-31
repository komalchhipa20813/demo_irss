<?php

namespace App\Http\Controllers;

use App\Exports\ExportEmployee;
use App\Models\{Department,Designation,IrssBranch,Role,User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /* display employee dashboard */
    public function index()
    {
        return view('pages.user.index');
    }

    /* listing of employee */
    public function listing()
    {
        $users = User::where('status','!=',2)->where('id','!=',1)->with('role','branch','department')->latest()->get(['id','first_name','last_name','email','role_id','irss_branch_id','department_id','code']);
        $records = [];
        $permissionList = permission();
        foreach ($users as $key => $row) {
            $button = '';
            if(in_array("129", $permissionList)){
                $button .= '<button class="btn btn-sm btn-success m-1 change-pwd"  data-id="'.encryptid($row['id']).'" >
                <i class="mdi mdi-account-key"></i>
                </button>';
            }

            if(in_array("45", $permissionList)){
                $button .= '<a href="'.route('employee.show',encryptid($row['id'])).'"><button class="btn btn-sm btn-success m-1"  data-id="'.encryptid($row['id']).'" >
                <i class="mdi mdi-view-module"></i>
                </button></a>';
            }
            if(in_array("47", $permissionList)){
                $button .= '<a href="'.route('employee.edit',encryptid($row['id'])).'"><button class=" btn btn-sm btn-success m-1">
                <i class="mdi mdi-square-edit-outline"></i>
                </button></a>';
            }
            if(in_array("48", $permissionList)){
                $button .= '<button class="delete btn btn-sm btn-danger m-1" data-id="'.encryptid($row['id']).'">
                <i class="mdi mdi-delete"></i>
                </button>';
            }
            $records[] = array(
                '0' => $key+1,
                '1'=> $row->code,
                '2' => $row->prefix.' '.$row->first_name.' '.$row->last_name,
                '3' => $row->email,
                '4' => $row->role->title,
                '5' => $row->branch->name,
                '6' => $row->department->name,
                '7'=>$button
            );
        }
        return response(['data'=>$records]);
    }
    /* checking user-email for availability */
    public function employee_check(Request $request) {
        if(isset($request) && $request->email && $request->id){
        $employee = User::where('email', $request->email)->where('id','!=',decryptid($request->id))->where('status','=',1)->first('id');
        return(!is_null($employee))?true:false;
        }else{
            return false;
        }
    }
    /* checking user-email for availability */
    public function code_check(Request $request) {
        if(isset($request) && $request->code && $request->id){
        $employee = User::where('code', $request->code)->where('id','!=',decryptid($request->id))->where('status','=',1)->first('id');
        return(!is_null($employee))?true:false;
        }else{
            return false;
        }
    }
    /* redirect to add employee data page */
    public function create()
    {
        try{
            $data['roles'] = Role::where('status',1)->where('id','!=',1)->get(['id','title']);
            $data['irss_branches'] = IrssBranch::where('status',1)->get(['id','name']);
            $data['departments'] = Department::where('status',1)->get(['id','name']);
            $data['designations'] = Designation::where('status',1)->get(['id','name']);
            $data['employee']=null;
            return view('pages.user.create',compact('data'));
        }catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
            return response($response);
        }
    }

    /* store or update user */
    public function store(Request $request)
    {
        try{
            $request->validate(
                [
                    'role' => 'required',
                    'branch' => 'required',
                    'department' => 'required',
                    'designation' => 'required',
                    'email' => 'required',
                    'code' => 'required',
                    'password' => decryptid($request->employee_id) == 0 ? 'required' : '',
			        'confirmpassword'  => decryptid($request->employee_id) == 0 ? 'required' : '' . '|same:password',
                    'prefix' => 'required',
                    'first_name' => 'required',
                    'middle_name' => 'required',
                    'last_name' => 'required',
                    'phone' => 'required',
                    'address' => 'required',
                    'gender' => 'required',
                    'dob' => 'required',
                    'joining_date' => 'required',

                ]
            );
            $userdata = "";

            if(isset($request->image)){
                $image = time() . 'emp_' . strtolower(substr($request->first_name, 0, 3)) . '.' . $request->image->extension();
                $request->image->move(public_path('store/employee/profile/'), $image);
            } else {
                $userdata =  User::find(decryptid($request->employee_id));
                $image = empty($userdata)?null:$userdata->image;

            }
            User::updateOrCreate(
                [
                    'id' => decryptid($request->employee_id),
                ],
                [
                    'image' => $image,
                    'role_id' => $request->role,
                    'irss_branch_id' => $request->branch,
                    'department_id' => $request->department,
                    'designation_id' => $request->designation,
                    'email' => $request->email,
                    'code' => $request->code,
                    'password' =>decryptid($request->employee_id) == 0 ? Hash::make($request->password) : $userdata->password,
                    'prefix' => $request->prefix,
                    'first_name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'gender' => $request->gender,
                    'dob' => $request->dob,
                    'joining_date' => $request->joining_date,
                    'anniversary_date' =>(isset($request->anniversary_date))? $request->anniversary_date:null,
                    'salary' =>(isset($request->salary))? $request->salary:null,
                    'account_number' =>(isset($request->salary_account))? $request->salary_account:null,
                    'ifsc_code' =>(isset($request->ifsc_code))? $request->ifsc_code:null,
                    'holder_name' =>(isset($request->account_holder))? $request->account_holder:null,
                ]

            );
            $response = [
				'status' => true,
				'message' => 'Employee ' . (decryptid($request->employee_id) == '0' ? 'Created' : 'Updated') . ' Successfully',
				'icon' => 'success',
				'redirect_url' => "employee",
			];
        }catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' =>'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
                'redirect_url' => "employee",
            ];
        }
        return response($response);
    }

    /* display all details */
    public function show($id)
    {
        try{
            $employee = User::with('branch', 'role', 'department','designation')->find(decryptid($id));
            return view('pages.user.show', compact('employee'));
        }catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
            return response($response);
        }
    }

    /* redirect to edit page */
    public function edit($id)
    {
        try{
            $data['roles'] = Role::where('status',1)->where('id','!=',1)->get(['id','title']);
            $data['irss_branches'] = IrssBranch::where('status',1)->get(['id','name']);
            $data['departments'] = Department::where('status',1)->get(['id','name']);
            $data['designations'] = Designation::where('status',1)->get(['id','name']);
            $data['employee']= User::where('status',1)->find(decryptid($id));
            return view('pages.user.create',compact('data'));
        }catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
            return response($response);
        }
    }

    /* Deleting Employee Data */
    public function destroy($id)
    {
        try{
            $update = ['status'=>2];
            User::where('id', decryptid($id))->update($update);
            $response = [
                'status' => true,
                'message' => "Employee Data Deleted Successfully",
                'icon' => 'success',
            ];
        }catch(\Throwable $e){
            $response = [
                'status' => false,
                'message' => "Something Went Wrong! Please Try Again.",
                'icon' => 'error',
            ];
        }
        return response($response);
    }

    public function export() 
    {
        return Excel::download(new ExportEmployee, 'employee.xlsx');
    }

    public function changePassword(Request $request)
    {
        try{
            $request->validate(
                [
                    'password' => 'required',
			        'confirmpassword'  => 'required' . '|same:password',
                ]
            );

            $user=User::where('id',decryptid($request->id))->first();
            if(!empty($user))
            {
                $user->update(['password' => Hash::make($request->password)]);

                $response = [
                    'status' => true,
                    'message' => 'Password Change Successfully',
                    'icon' => 'success',
                    'redirect_url' => "employee",
                ];
            }
        }catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' =>'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
                'redirect_url' => "employee",
            ];
        }
        return response($response);

    }
}
