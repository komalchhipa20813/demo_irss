<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Company,CompanyBranch,BranchImdName,Country,State,City,ProductCompany};


class CompanyController extends Controller
{
   //Dashboard Of Company
    public function index()
    {
        $country=Country::where('status',1)->get(['id','name']);
        return view('pages.settings.company.company.company',compact('country'));
    }

    /*Storing And Updating Data Of Company*/
    public function store(Request $request)
    {
        $request->validate(
            [
                'company_name' => 'required',
				'address' => 'required',
				'city_name'=>'required',
            ]
        );
        try {
            Company::updateOrCreate([
                'id' => decryptid($request->id),
            ],[
                'name' => $request->company_name,
                'address'=>$request->address,
                'city_id'=>$request->city_name,
            ]);
            $response = [
                'status' => true,
                'message' => 'Company data '.(decryptid($request->id) == 0 ? 'Added' : 'Updated').' Successfully',
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

    //Listing Data Of Company
    public function listing(){
        $data['companyData']= Company::where('status',1)->with('city')->latest()->get(['id','name','address','city_id']);
        $result = [];
        $permissionList = permission();
        foreach ($data['companyData'] as $key=>$company) {
            $button = '';
            if(in_array("19", $permissionList)){
                $button .= '<button class="edit_company btn btn-sm btn-success m-1"  data-id="'.encryptid($company['id']).'">
                <i class="mdi mdi-square-edit-outline"></i>
                </button>';
            }
            if(in_array("20", $permissionList)){
                $button .= '<button class="delete btn btn-sm btn-danger m-1" data-id="'.encryptid($company['id']).'">
                <i class="mdi mdi-delete"></i>
                </button>';
            }
            $result[] = array(
            "no"=>$key+1,
            "name"=>ucfirst($company->name),
            "address"=>ucfirst($company->address),
            "country"=>ucfirst($company->city->state->country->name),
            "state"=>ucfirst($company->city->state->name),
            "city"=>ucfirst($company->city->name),
            "action"=>$button
            );
        }
        return response()->json(['data'=>$result]);
    }

   /* Show Data Of Company*/
    public function edit($id)
    {
        try {
            $company = Company::where('id',decryptid($id))->with('city')->first(['id','name','address','city_id']);
            $company['states'] = State::where('country_id',$company['city']['state']['country_id'])->where('status',1)->get(['id','name']);
            $company['cities'] = City::where('state_id',$company['city']['state_id'])->where('status',1)->get(['id','name']);
            $response = [
                'data'=>$company,
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

    /*Remove Data Of Company.*/
    public function destroy($id)
    {
        try {
            $update=['status' => 2];
            Company::where('id',decryptid($id))->update($update);
            CompanyBranch::select('company_id')->where('company_id',decryptid($id))->update($update);
            BranchImdName::select('company_id')->where('company_id',decryptid($id))->update($update);
            ProductCompany::where('company_id', decryptid($id))->delete();
            $response = [
                'status' => true,
                'message' => "Company Data Deleted Successfully",
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

    /*Check Availability Of Company*/
     public function checkCompanyName(Request $request){
        if(isset($request) && $request->name && $request->id){
            $company = Company::where('name',$request->name)->where('id','!=',decryptid($request->id))->where('city_id',$request->city_id)->where('status',1)->first('name');
            return(!is_null($company))? true :false;
        }else{
            return false;
        }
    }
}
