<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    // View Designation Dashboard
    public function index()
    {
        return view('pages.settings.general.designation.designation');
    }

    // Add Or Update Designation Details
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        try {
            Designation::updateOrCreate([
                'id' => decryptid($request['designation_id']),
            ],
            [
                'name' => $request->name
            ]);
            $response = [
                'status' => true,
                'message' => 'Designation '.(decryptid($request['designation_id'])==0 ? 'Added' : 'Updated ').' Successfully',
                'icon' => 'success',
            ];
        } catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
        }
        return response($response);
    }

    // Check Availability Of The Designation Name
    public function checkDesignation(Request $request){
        if(isset($request) && $request->id && $request->name){
            $designation = Designation::where('name', $request->name)->where('status', 1)->where('id','!=',decryptid($request->id))->first('name');
            return (!is_null($designation)) ? true : false;
        }else{
            return false;
        }
    }

    // Listing Designation Details
    public function listing(){
        $designations = designation::where('status',1)->latest()->get(['id','name']);
        $result=[];
        $permissionList = permission();
        foreach($designations as $key=>$designation){
            $button = '';
            if(in_array("11", $permissionList)){
                $button .= '<button class="edit_designation btn btn-sm btn-success m-1" data-id="'.encryptid($designation->id).'" >
                <i class="mdi mdi-square-edit-outline"></i>
                </button>';
            }
            if(in_array("12", $permissionList)){
                $button .= '<button class="delete btn btn-sm btn-danger m-1" data-id="'.encryptid($designation->id).'">
                <i class="mdi mdi-delete"></i>
                </button>';
            }
            $result[] = array(
            "no"=>($key +1),
            "name"=>ucfirst($designation->name),
            "action"=>$button
            );
        }
        return response(['data'=>$result]);
    }

    // Show Data Of The Designation For An Update
    public function edit($id)
    {
        try {
            $designation =Designation::where('id',decryptid($id))->first('name');
            $response = [
                'data' => $designation,
                'status' => true,
            ];
        } catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => "Something Went Wrong! Please Try Again.",
                'icon' => 'error',
            ];
        }
        echo json_encode($response);
    }

    // Delete Selected Designation Record
    public function destroy($id)
    {
        try {
            Designation::where('id',decryptid($id))->update(['status' => 2]);
            $response = [
                'status' => true,
                'message' => "Designation Deleted Successfully",
                'icon' => 'success',
            ];
        } catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => "Something Went Wrong! Please Try Again.",
                'icon' => 'error',
            ];
        }
        return response($response);
    }
}
