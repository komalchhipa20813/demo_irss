<?php

namespace App\Http\Controllers;

use App\Models\{Permission,Role,Role_Permission};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller {
    /* Display Dashboard Of Role */
    public function index() {
        return view('pages.role-permission.role');
    }

    /* Listing Of Role */
    public function listing() {
        $roles = Role::where('status',1);
        $roles= Auth::user()->id==1?$roles->latest()->get():$roles->where('id','!=',1)->latest()->get();
        $records = [];
        $permissionList = permission();
        foreach ($roles as $key => $row) {
            $button = '';
            if (in_array("37", $permissionList)) {
                $button .= '<a href="' . route('role.show', encryptid($row['id'])) . '"><button class="btn btn-sm btn-success m-1"  data-id="' . encryptid($row['id']) . '" >
                <i class="mdi mdi-view-module"></i>
                </button></a>';
            }
            if (in_array("39", $permissionList)) {
                $button .= '<a href="' . route('role.edit', encryptid($row['id'])) . '"><button class=" btn btn-sm btn-success m-1">
                <i class="mdi mdi-square-edit-outline"></i>
                </button></a>';
            }
            if (in_array("40", $permissionList)) {
                $button .= '<button class="delete btn btn-sm btn-danger m-1" data-id="' . encryptid($row['id']) . '">
                <i class="mdi mdi-delete"></i>
                </button>';
            }
            $records[] = array(
                '0' => $key+1,
                '1' => $row->title,
                '2' => $button
            );
        }
        return response(['data'=>$records]);
    }
    /* Checking Role For Availability */
    public function rolecheck(Request $request) {
        if(isset($request) && $request->title && $request->id){
            $role = Role::where('title', $request->title)->where('status',1)->where('id','!=',decryptid($request->id))->first('id');
            return (!is_null($role))?true:false;
        }
    }
    /* Create Role */
    public function create() {
        $data['permissions'] = Permission::where('status', 1)->get(['id','name']);
        $data['role'] = null;
        $data['p_id'] = null;
        return view('pages.role-permission.role-add', compact('data'));
    }
    /* Storing Or Updating Data To Database */
    public function store(Request $request) {
        try{
            $request->validate(
                [
                    'title' => 'required',
                    'permission' => 'required|array',
                ]
            );
            $result = Role::updateOrCreate(
                [
                    'id' => decryptid($request['role_id']),
                ],
                [
                    'title' => $request->title,
                ]
            );
            Role_Permission::where('role_id', decryptid($request['role_id']))->delete();
            foreach ($request['permission'] as $permission) {
                $permissions[] = [
                    "role_id" => $result->id,
                    "permission_id" => $permission
                ];
            }
            $x=Role_Permission::insert($permissions);
            if ($x) {
                $response = [
                    'status' => true,
                    'message' => 'Role ' . (decryptid($request['role_id']) == 0 ? 'Added' : 'Updated ') . ' Successfully',
                    'icon' => 'success',
                    'redirect_url' => "role",
                ];
            } else {
                $result->delete();
                $response = [
                    'status' => false,
                    'message' => 'Something Went Wrong! Please Try Again.',
                    'icon' => 'error',
                ];
            }
        }catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
        }
        return response($response);
    }
    /* Edit Role */
    public function edit($id) {
        try{
            $role = Role::where('id', decryptid($id))->with('role_role_permission')->first(['id','title']);
            $data['role'] = $role;
            $data['permissions'] = Permission::where('status', 1)->get(['id','name']);
            if (!is_null($role)) {
                $i = 0;
                $p_id = [];
                foreach ($role->role_role_permission as $permission) {
                    $p_id[$i] = $permission->permission_id;
                    $i++;
                }
                $title = $role->title;
                $data['p_id'] = array("p_id" => $p_id);
                return view('pages.role-permission.role-add', compact('data'));
            } else {
                return view('pages.role-permission.role');
            }
        }catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
            return response($response);
        }
    }
    /* Delete Data */
    public function destroy($id) {
        try{
            Role::where('id', decryptid($id))->update(['status'=>2]);
            $response = [
                'status' => true,
                'message' => "Role Deleted Successfully",
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
    /* Show Detail Of Role */
    public function show($id) {
        try{
            $role = Role::where('id', decryptid($id))->with('role_role_permission')->first(['id','title']);
            if (!empty($role)) {
                $i = 0;
                $p_id = [];
                foreach ($role->role_role_permission as $role_permission) {
                    $permission = $role_permission->load('permission_role_permission')->permission_role_permission;
                    $p_id[$i] = $permission->name;
                    $i++;
                }
                $title = $role['title'];
                $data = array("title" => $title, "p_id" => $p_id);
            } else {
                return redirect(route('role.index'));
            }
            return view('pages.role-permission.role-show', compact('data'));
        }catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
            return response($response);
        }
    }
}
