<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Country,State,City};

class CityController extends Controller
{
    /*Dashboard Of City*/
    public function index()
    {
        $country = Country::where('status','1')->get(['id','name']);
        return view('pages.settings.address.city.city',compact('country'));
    }

    //Storing And Updating Data Of City
    public function store(Request $request)
    {
        $request->validate(
            [
                'country_name' => 'required',
				'state_name' => 'required',
                'city' => 'required',
                'rto_code'=>'required',
            ]
        );
        try {
            City::updateOrCreate([
                'id' => decryptid($request->id),
            ],[
                'state_id'=>$request->state_name,
                'name'=>$request->city,
                'rto_code'=>$request->rto_code,
            ]);

            $response = [
                'status' => true,
                'message' => 'City Data '.(decryptid($request->id) ==0 ? 'Added' : 'Updated').' Successfully',
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

  //Listing Data Of City
  public function listing(){
    $data['cityData']= City::where('status',1)->with('state')->get(['id','state_id','name','rto_code']);
    $result = [];
    $permissionList = permission();
    foreach ($data['cityData'] as $key=>$city) {
        $button = '';
        if(in_array("63", $permissionList)){
            $button .= '<button class="edit_city btn btn-sm btn-success m-1"  data-id="'.encryptid($city['id']).'">
            <i class="mdi mdi-square-edit-outline"></i>
            </button>';
        }
        if(in_array("64", $permissionList)){
            $button .= '<button class="delete btn btn-sm btn-danger m-1" data-id="'.encryptid($city['id']).'">
            <i class="mdi mdi-delete"></i>
            </button>';
        }
        $result[] = array(
        "no"=>$key+1,
        "country_name"=>ucfirst($city->state->country->name),
        "state_name"=>ucfirst($city->state->name),
        "city_name"=>ucfirst($city->name),
        "rto_code"=>ucfirst($city->rto_code),
        "action"=>$button
        );
    }
    return response()->json(['data'=>$result]);
}

    //Show Data Of City
    public function edit($id)
    {
        try {
            $data['city'] = City::where('id',decryptid($id))->with('state')->first(['id','state_id','name','rto_code']);
            $data['states'] = State::where('country_id',$data['city']['state']['country_id'])->where('status',1)->get(['id','name']);
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

    //Remove Data Of City
    public function destroy($id)
    {
        try {
            $update['status']=2;
            City::where('id',decryptid($id))->update($update);
            $response = [
                'status' => true,
                'message' => "City Data Deleted Successfully",
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

     /*Check Availability Of City*/
     public function city_check(Request $request){
        if(isset($request) && $request->state && $request->name && $request->id){
         $city = City::where('state_id',$request->state)->where('name',$request->name)->where('id','!=',decryptid($request->id))->where('status',1)->first('name');
         return(!is_null($city))? true :false;
         }else{
            return false;
         }
     }

     /*Check Availability Of City RTO Code*/

     
     public function checkCityRTOCode(Request $request){
        if(isset($request) && $request->state && $request->name && $request->id){
         $city = City::where('state_id',$request->state)->where('rto_code',$request->name)->where('id','!=',decryptid($request->id))->where('status',1)->first('rto_code');
         return(!is_null($city))? true :false;
         }else{
            return false;
         }
     }

     public function getRTOCodeCity(Request $request)
    {

        try {
            $rto_code = substr($request->code, 0, 4);
            $rtoCode = City::where('rto_code',$rto_code)->where('status',1)->first();
            $response = [
                'rtoCode'=>$rtoCode,
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

    /*Check Availability Of RTO*/
    public function check_rtoCode_pattern(Request $request){
        if(isset($request) && $request->rto_code){
        return(!preg_match("/^[A-Z]{2}[0-9]{2}/",$request->rto_code))? true :false;
        }else{
            return false;
        }
    }
}
