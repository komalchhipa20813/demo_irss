<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /* Display Dashboard Of The Department  */
    public function index()
    {
        return view('pages.settings.general.department.department');
    }
    /* Storing And Updating Department Data */
    public function store(Request $request)
    {
        $request->validate([
            'department_name' => 'required',
        ]);
        try{
            Department::updateOrCreate([
                'id' => decryptid($request->department_id),
            ],
            [
                'name' => $request['department_name'],
            ]);
            $response = [
                'status' => true,
                'message' => 'Department '.(decryptid($request->department_id)==0 ? 'Added' : 'Updated ').' Successfully',
                'icon' => 'success',
            ];
        }catch(\Throwable $e){
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
        }
        return response($response);
    }

    /* Check Availability Of The Department Name */
    public function checkDepartment(Request $request){
        if(isset($request) && $request->id && $request->name){
            $department = Department::where('name', $request->name)->where('id','!=',decryptid($request->id))->where('status',1)->first('name');
            return (!is_null($department)) ?true : false;
        }else{
            return false;
        }
    }

    /* Listing Of Department Data */
    public function listing(){
        $departments = Department::where('status',1)->latest()->get(['id','name']);
        $result=[];
        $permissionList = permission();
        foreach($departments as $key=>$department){
            $button = '';
            if(in_array("7", $permissionList)){
                $button .= '<button class="edit_department btn btn-sm btn-success m-1" data-id="'.encryptid($department->id).'" >
                <i class="mdi mdi-square-edit-outline"></i></button>';
            }
            if(in_array("8", $permissionList)){
                $button .= '<button class="delete btn btn-sm btn-danger m-1" data-id="'.encryptid($department->id).'">
                <i class="mdi mdi-delete"></i>
                </button>';
            }
            $result[] = array(
            "no"=>$key+1,
            "name"=>ucfirst($department->name),
            "action"=>$button
            );
        }
        return response(['data'=>$result]);
    }
    /* Show Data Of The Department For An Update */
    public function show($id)
    {
        try {
            $department = Department::where('id',decryptid($id))->first('name');
            $response = [
                'data' => $department,
                'status' => true,
            ];
        } catch (\Throwable $th) {
            $response = [
                'status' => false,
                'message' => "Something Went Wrong! Please Try Again.",
                'icon' => 'error',
            ];
        }
        echo json_encode($response);
    }
    /* Delete Department Data */
    public function destroy($id)
    {
        try {
            Department::where('id',decryptid($id))->update(['status' => 2]);
            $response = [
                'status' => true,
                'message' => "Department Deleted Successfully",
                'icon' => 'success',
            ];
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
