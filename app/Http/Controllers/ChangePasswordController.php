<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    /* display dashboard of change password */
    public function index()
    {
        return view('auth.change-password');
    }
    /* checking old password */
    public function oldPasswordCheck(Request $request)
    {
        if(isset(Auth::user()->id)){
            if(!Hash::check($request->password, Auth::user()->password)){
                return true;
            }
        }elseif(isset(Auth::guard('fdo')->user()->id)){
            if(!Hash::check($request->password, Auth::guard('fdo')->user()->password)){
                return true;
            }
        }else{
            if(!Hash::check($request->password, Auth::guard('agent')->user()->password)){
                return true;
            }
        }
    }
    /* update password */
    public function update(Request $request)
    {
        $request->validate([
            'oldpassword' => 'required',
            'password' => 'required',
            'confirmpassword' => 'required:same:new_password',
        ]);
        if (strcmp($request->get('oldpassword'), $request->get('password')) == 0) {
            return response()->json([
                'status' => false,
                'new_message' => 'New Password cannot be same as your old password',
            ]);
        }
        if(isset(Auth::user()->id)){
            $user = Auth::user();
            $logout='logout';
        }elseif(isset(Auth::guard('fdo')->user()->id)){
            $user = Auth::guard('fdo')->user();
            $logout='fdo-agent/logout';
        }else{
            $user = Auth::guard('agent')->user();
            $logout='fdo-agent/logout';
        }
        if (!(Hash::check($request->get('oldpassword'), $user->password))) {
            return response()->json([
                'status' => false,
                'message' => 'Your old password does not matches with the password.',
            ]);
        }
        $user->password = Hash::make($request->password);
        if ($user->save()) {
            return response()->json([
                'status' => true,
                'message' => 'password updated successfully',
                'icon' => 'success',
                'redirect_url' => $logout,
            ]);
        }
    }
}
