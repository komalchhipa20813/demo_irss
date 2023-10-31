<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{State,Country,City};

class StateController extends Controller
{
    /*Dashboard Of State*/
    public function index()
    {
        $country = Country::where('status','1')->get(['id','name']);
        return view('pages.settings.address.state.state',compact('country'));
    }

    //Storing And Updating Data Of State
    public function store(Request $request)
    {
        $request->validate(
            [
                'country_name' => 'required',
                'state_name' => 'required',
            ]
        );
        try{
            State::updateOrCreate([
                'id' => decryptid($request->id),
            ],[
                'country_id' => $request->country_name,
                'name'=>$request->state_name,

            ]);
            $response = [
                'status' => true,
                'message' => 'State Data '.(decryptid($request->id) ==0 ? 'Added' : 'Updated').' Successfully',
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

    //Listing Data Of State
    public function listing(){
        $data['stateData']= State::where('status',1)->with('country')->get(['id','country_id','name']);
        $result = [];
        $permissionList = permission();
        foreach ($data['stateData'] as $key=>$state) {
            $button = '';
            if(in_array("59", $permissionList)){
                $button .= '<button class="edit_state btn btn-sm btn-success m-1" data-id="'.encryptid($state['id']).'">
                <i class="mdi mdi-square-edit-outline"></i>
                </button>';
            }
            if(in_array("60", $permissionList)){
                $button .= '<button class="delete btn btn-sm btn-danger m-1" data-id="'.encryptid($state['id']).'">
                <i class="mdi mdi-delete"></i>
                </button>';
            }

            $result[]= array(
            "no"=>$key+1,
            "country_name"=>ucfirst($state->country->name),
            "state_name"=>ucfirst($state->name),
            "action"=>$button
            );
        }
        return json_encode(['data'=>$result]);
    }

    /* Show Data Of State*/
    public function edit($id)
    {
        try {
            $data = State::where('id',decryptid($id))->first(['id','country_id','name']);
            if(!is_null($data) ){
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

    //Remove Data Of State
    public function destroy($id)
    {
        try {
            $update['status'] = 2;
            State::where('id',decryptid($id))->update($update);
            City::where('state_id',decryptid($id))->update($update);
            $response = [
                'status' => true,
                'message' => "State Data Deleted Successfully",
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

    /*Check Availability Of State*/
    public function state_check(Request $request){
        if(isset($request) && $request->country_name && $request->name && $request->id){
        $state = State::where('country_id',$request->country_name)->where('name',$request->name)->where('id','!=',decryptid($request->id))->where('status',1)->first('name');
        return(!is_null($state))? true :false;
        }else{
            return false;
        }
    }
    /* Get State Wise City Data */
    public function get_city_name(Request $request)
    {
        $data['city'] = City::where("state_id",$request->state_id)->where('status',1)->get(["name", "id"]);
        return response()->json($data);
    }
}
