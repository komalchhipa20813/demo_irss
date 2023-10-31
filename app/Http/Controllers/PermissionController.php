<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
class PermissionController extends Controller {

    /* Display Dashboard Of Permission */
    public function index() {
        return view('pages.role-permission.permission');
    }

    /* Listing Of Permission */
    public function listing() {
        $permissions = Permission::latest()->where('status', 1)->get(['id','name']);
        $records = [];
        $permissionList = permission();
        foreach ($permissions as $key => $row) {
            if (in_array("43", $permissionList)) {
                $button = '';
                $button .= '<button class="permission_edit btn btn-sm btn-success m-1" data-id="' . encryptid($row['id']) . '" >
                <i class="mdi mdi-square-edit-outline"></i>
                </button>';
            }
            if (in_array("44", $permissionList)) {
                $button .= '<button class="delete btn btn-sm btn-danger m-1" data-id="' . encryptid($row['id']) . '">
                <i class="mdi mdi-delete"></i>
                </button>';
            }
            $records[] = array(
                '0' => $key+1,
                '1' => $row->name,
                '2' => $button
            );
        }
        return response(['data'=>$records]);
    }
    /* Checking Permission For Availability */
    public function checkPermission(Request $request) {
        if(isset($request) && $request->name && $request->id){
            $permission = Permission::where('name', $request->name)->where('status', '!=', 2)->where('id','!=',decryptid($request->id))->first('id');
            return (!is_null($permission))?true:false;
        }
    }
    /* Storing Or Updating Data To Database */
    public function store(Request $request) {
        $request->validate(
            [
                'name' => 'required',
            ]
        );
        try{
            Permission::updateOrCreate(
                [
                    'id' => decryptid($request['permission_id']),
                ],
                [
                    'name' => $request['name'],
                ]
            );
            $response = [
                'status' => true,
                'message' => 'Permission ' . (decryptid($request['permission_id']) == 0 ? 'Added' : 'Updated ') . ' Successfully',
                'icon' => 'success',
            ];
        }catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
        }
        return response($response);
    }
    /* Fetch Data For Update */
    public function show($id) {
        try{
            $permission = Permission::where('id', decryptid($id))->first('name');
            $response = [
                'data' => $permission,
                'status' => true,
                'name' => $permission->name,
            ];
        }catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
        }
        return response($response);
    }

    /* Delete Data */
    public function destroy($id) {
        try{
            Permission::where('id', decryptid($id))->update(['status'=>2]);
            $response = [
                'status' => true,
                'message' => "Permission Deleted Successfully",
                'icon' => 'success',
            ];
        }catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
        }
        return response($response);
    }
}
