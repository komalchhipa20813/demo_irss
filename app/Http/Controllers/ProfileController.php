<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function viewProfile()
    {
        $user = Auth::user();
        if (isset($user)) {
            $employee = Auth::user();
            return view('pages.profile.profile', compact('employee'));
        } else {
            return redirect()->back();
        }
    }
    public function updateProfile(Request $request)
    {
        try {
            $request->validate(
                [
                'image' => decryptid($request->employee_id) == 0 ? 'required' : '',
                'email' => 'required',
                'prefix' => 'required',
                'first_name' => 'required',
                'middle_name' => 'required',
                'last_name' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'gender' => 'required',
                'dob' => 'required'
            ]
            );
            $userdata = "";
            if(decryptid($request->employee_id)==0 || isset($request->image)){
                $image = time() . 'emp_' . strtolower(substr($request->first_name, 0, 3)) . '.' . $request->image->extension();
                $request->image->move(public_path('store/employee/profile/'), $image);
            } else {
                $userdata =  Auth::user();
                $image = $userdata->image;

            }
            User::updateOrCreate(
                [
                    'id' => decryptid($request->employee_id),
                ],
                [
                    'image' => $image,
                    'email' => $request->email,
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
                ]

            );
            $response = [
				'status' => true,
				'message' => 'Profile Updated Successfully',
				'icon' => 'success',
				'redirect_url' => "profile",
			];
        } catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' =>'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
                'redirect_url' => "dashboard",
            ];
        }
        return response($response);
    }
}
