<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Country,State,City};
class CountryController extends Controller
{
    //Dashboard Of Country
    public function index()
    {
        return view('pages.settings.address.country.country');
    }

    //Storing And Updating Data Of Country
    public function store(Request $request)
    {
        $request->validate(
            [
                'country_name' => 'required',
            ]
        );
        try {
            Country::updateOrCreate([
                'id' => decryptid($request->id),
            ],[
                'name' => $request->country_name,
            ]);

            $response = [
                'status' => true,
                'message' => 'Country Data '.(decryptid($request->id) ==0 ? 'Added' : 'Updated').' Successfully',
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

    //Listing Data Of Country
    public function listing(){
        $data['countryData']= Country::where('status',1)->get(['id','name']);
        $result = [];
        $permissionList = permission();
        foreach ($data['countryData'] as $key=> $country) {
            $button = '';
            if(in_array("55", $permissionList)){
                $button .= '<button class="edit_country btn btn-sm btn-success m-1"  data-id="'.encryptid($country['id']).'">
                <i class="mdi mdi-square-edit-outline"></i>
                </button>';
            }
            if(in_array("56", $permissionList)){
                $button .= '<button class="delete btn btn-sm btn-danger m-1" data-id="'.encryptid($country['id']).'">
                <i class="mdi mdi-delete"></i>
                </button>';
            }

            $result[] = array(
            "no"=>$key+1,
            "name"=>ucfirst($country->name),
            "action"=>$button
            );
        }
        return response()->json(['data'=>$result]);
    }

    //Show Data Of Country
    public function edit($id)
    {

        try {
            $data['country'] = Country::where('id',decryptid($id))->first('name');
            $response = [
                'data'=>$data,
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

    /* Remove Data Of Country */
    public function destroy($id)
    {
        try {
            $update['status'] = 2;
            Country::where('id',decryptid($id))->update($update);
            State::where('country_id',decryptid($id))->update($update);
            $state_ids=State::where('country_id', decryptid($id))->pluck('id')->toArray();
            City::whereIn('state_id',$state_ids)->update($update);
            $response = [
                'status' => true,
                'message' => "Country Data Deleted Successfully",
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

    /*Check Availability Of Country*/
     public function country_check(Request $request){
        if(isset($request) && $request->name && $request->id){
        $country = Country::where('name',$request->name)->where('id','!=',decryptid($request->id))->where('status',1)->first('name');
        return(!is_null($country))? true :false;
        }else{
            return false;
        }
    }
    /* Get Country Wise State Data */
    public function get_state_name(Request $request)
    {
        $data['state'] = State::where("country_id",$request->country)->where('status',1)->get(["name", "id"]);
        return response()->json($data);
    }
}
