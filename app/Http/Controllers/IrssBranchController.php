<?php

namespace App\Http\Controllers;

use App\Models\{Country,IrssBranch,State,City, User};
use Illuminate\Http\Request;

class IrssBranchController extends Controller
{
    //Dashboard Of IRSS Branch
    public function index()
    {
        $country=Country::where('status',1)->get(['id','name']);
        return view('pages.settings.general.irss_branch.irss_branch',compact('country'));
    }
    //Storing And Updating Data Of IRSS Branch
    public function store(Request $request)
    {
        if(isset($request) && $request->id){
            $request->validate(
                [
                    'IrssBranch_name' => 'required',
                    'address' => 'required',
                    'country_name'=>'required',
                    'state_name'=>'required',
                    'city_name'=>'required',
                    'IrssBranch_inward_code'=>'required',
                ]
            );

            try {
                IrssBranch::updateOrCreate([
                    'id' => decryptid($request->id),
                ],[
                    'name' => $request->IrssBranch_name,
                    'address'=>$request->address,
                    'city_id'=>$request->city_name,
                    'policy_inward_code'=>$request->IrssBranch_inward_code,
                ]);
                $response = [
                    'status' => true,
                    'message' => 'IRSS Branch Data '.(decryptid($request->id) == 0 ? 'Added' : 'Updated').' Successfully.',
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
    }

    //Listing Data Of IRSS Branch
    public function listing(){
        $data['irssBranchData']= IrssBranch::where('status',1)->with('city')->latest()->get(['id','name','address','city_id','policy_inward_code']);
        $result = [];
        $permissionList = permission();
        foreach ($data['irssBranchData'] as $key=>$irssBranch) {

            $button = '';
            if(in_array("3", $permissionList)){
                $button .= '<button class="edit_irss_branch btn btn-sm btn-success m-1"  data-id="'.encryptid($irssBranch['id']).'">
                <i class="mdi mdi-square-edit-outline"></i>
                </button>';
            }
            if(in_array("4", $permissionList)){
                $button .= '<button class="delete btn btn-sm btn-danger m-1" data-id="'.encryptid($irssBranch['id']).'">
                <i class="mdi mdi-delete"></i>
                </button>';
            }

            $result[] = array(
            "no"=>$key+1,
            "name"=>ucfirst($irssBranch->name),
            "inward"=>strtoupper($irssBranch->policy_inward_code),
            "country"=>ucfirst($irssBranch->city->state->country->name),
            "state"=>ucfirst($irssBranch->city->state->name),
            "city"=>ucfirst($irssBranch->city->name),
            "address"=>ucfirst($irssBranch->address),
            "action"=>$button
            );
        }

        return response(['data'=>$result]);
    }

    /* Show Data Of IRSS Branch*/
    public function edit($id)
    {
        try {
            $irssBranch=IrssBranch::where('id',decryptid($id))->with('city')->first(['id','name','address','city_id','policy_inward_code']);
            $irssBranch['states'] = State::where('country_id',$irssBranch['city']['state']['country_id'])->where('status',1)->get(['id','name']);
            $irssBranch['cities'] = City::where('state_id',$irssBranch['city']['state_id'])->where('status',1)->get(['id','name']);
            $response = [
                'data'=>$irssBranch,
                'status'=>true,
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

    /*Remove Data Of IRSS Branch.*/
    public function destroy($id)
    {
        try {
            IrssBranch::where('id',decryptid($id))->update(['status' => 2]);
            user::where('irss_branches_id',decryptid($id))->update(['status' => 2]);
            $response = [
                'status' => true,
                'message' => "IRSS Branch Data Deleted Successfully",
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

    /*Check Availability Of IRSS Branch*/
    public function checkBranch(Request $request){
        if(isset($request) && $request->name && $request->id){
            $irssBranch = IrssBranch::where('name',$request->name)->where('id','!=',decryptid($request->id))->where('city_id',$request->city_id)->where('status',1)->first('name');
            return(!is_null($irssBranch))?true:false;
        }else{
            return false;
        }
    }

    /*Check Availability Of IRSS Branch Inward Code */
    public function checkBranchInwardCode(Request $request){
        if(isset($request) && $request->name && $request->id){
            $irssBranch = IrssBranch::where('policy_inward_code',$request->name)->where('id','!=',decryptid($request->id))->where('city_id',$request->city_id)->where('status',1)->first('policy_inward_code');
            return(!is_null($irssBranch))?true:false;
        }else{
            return false;
        }
    }
    /* get sales manager of service branch */
    public function get_data(Request $request) {
        try{
            $response = [
                'status' => true,
                'sales_manager' => User::where('irss_branch_id',$request->branch_id)->where('id','!=',1)->where('status',1)->get(['id','first_name','middle_name','last_name'])
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
}
