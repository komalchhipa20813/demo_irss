<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\{CompanyBranch,Company,Country,State,City,BranchImdName};

class CompanyBranchController extends Controller
{
   //Dashboard Of Company Branch
    public function index()
    {
        $country=Country::where('status',1)->get(['id','name']);
        $company = Company::where('status','1')->get(['id','name']);
        return view('pages.settings.company.company_branch.company_branch',compact('company','country'));
    }

    //Storing And Updating Data Of Company Branch
    public function store(Request $request)
    {
        $request->validate(
            [
                'company_name' => 'required',
				'company_branch_name' => 'required',
                'address' => 'required',
                'country_name'=>'required',
                'state_name'=>'required',
                'city_name'=>'required',
            ]
        );
        try {
            CompanyBranch::updateOrCreate([
                'id' => decryptid($request->id),
            ],[
                'company_id' => $request->company_name,
                'name'=>$request->company_branch_name,
                'address'=>$request->address,
                'city_id'=>$request->city_name,
            ]);
            $response = [
                'status' => true,
                'message' => 'Company Branch Data '.(decryptid($request->id) ==0 ? 'Added' : 'Updated').' Successfully',
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

     //Listing Data Of Company Branch
     public function listing(){
        $data['companyBranchData']= CompanyBranch::where('status',1)->with('company','city')->latest()->get(['id','company_id','name','city_id','address']);
        $result = [];
        $permissionList = permission();
        foreach ($data['companyBranchData'] as $key=>$companyBranch) {
            $button = '';
            if(in_array("23", $permissionList)){
                $button .= '<button class="edit_company_branch btn btn-sm btn-success m-1"  data-id="'.encryptid($companyBranch['id']).'">
                <i class="mdi mdi-square-edit-outline"></i>
                </button>';
            }
            if(in_array("24", $permissionList)){
                $button .= '<button class="delete btn btn-sm btn-danger m-1" data-id="'.encryptid($companyBranch['id']).'">
                <i class="mdi mdi-delete"></i>
                </button>';
            }
            $result[] = array(
            "no"=>$key+1,
            "name"=>ucfirst($companyBranch->company->name),
            "company_branch_name"=>ucfirst($companyBranch->name),
            "country"=>ucfirst($companyBranch->city->state->country->name),
            "state"=>ucfirst($companyBranch->city->state->name),
            "city"=>ucfirst($companyBranch->city->name),
            "address"=>ucfirst($companyBranch->address),
            "action"=>$button
            );
        }
        return response()->json(['data'=>$result]);
    }

    /* Show Data Of Company Branch*/
    public function edit($id)
    {
        $companyBranch = CompanyBranch::where('id',decryptid($id))->with('city')->first(['id','company_id','name','city_id','address']);
        $companyBranch['states'] = State::where('country_id',$companyBranch['city']['state']['country_id'])->where('status',1)->get(['id','name']);
            $companyBranch['cities'] = City::where('state_id',$companyBranch['city']['state_id'])->where('status',1)->get(['id','name']);
        try {
            if(!is_null($companyBranch) ){
                $response = [
                    'data'=>$companyBranch,
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

    //Remove Data Of Company Branch
    public function destroy($id)
    {
        try {
            $update=['status' => 2];
            CompanyBranch::where('id',decryptid($id))->update($update);
            BranchImdName::select('Company_branch_id')->where('Company_branch_id',decryptid($id))->update($update);
            $response = [
                'status' => true,
                'message' => "Company Branch Data Deleted Successfully",
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

     /*Check Availability Of Company Branch*/
     public function checkCompanyBranch(Request $request){
        if(isset($request) && $request->company_name && $request->name && $request->id){
            $companyBranch = CompanyBranch::where('company_id',$request->company_name)->where('name',$request->name)->where('city_id',$request->city_id)->where('id','!=',decryptid($request->id))->where('status',1)->first('name');
            return(!is_null($companyBranch))? true :false;
        }else{
            return false;
        }
    }
}
