<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\{Company,CompanyBranch,BranchImdName};


class BranchImdNameController extends Controller
{
    //Dashboard Of Branch Imd
    public function index()
    {
        $company = Company::where('status','1')->get(['id','name']);
        return view('pages.settings.company.branch_imd.branch_imd',compact('company'));
    }

    //Storing And Updating Data Of Branch Imd
    public function store(Request $request)
    {
        $request->validate(
            [
                'company_name' => 'required',
				'company_branch_name' => 'required',
                'branch_imd_name' => 'required',

            ]
        );

        try {
            BranchImdName::updateOrCreate([
                'id' => decryptid($request->id),
            ],[
                'company_id' => $request->company_name,
                'company_branch_id'=>$request->company_branch_name,
                'name'=>$request->branch_imd_name,
            ]);
            $response = [
                'status' => true,
                'message' => 'Branch Imd Data '.(decryptid($request->id) ==0 ? 'Added' : 'Updated').' Successfully',
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

    //Listing Data Of Branch Imd
    public function listing(){
        $data['branchImbData']= BranchImdName::where('status',1)->with('company','companyBranch')->latest()->get(['id','company_id','company_branch_id','name']);
        $result = [];
        $permissionList = permission();
        foreach ($data['branchImbData'] as $key=>$branchImb) {
            $button = '';
            if(in_array("27", $permissionList)){
                $button .= '<button class="edit_branch_Imd btn btn-sm btn-success m-1"  data-id="'.encryptid($branchImb['id']).'">
                <i class="mdi mdi-square-edit-outline"></i>
                </button>';
            }
            if(in_array("28", $permissionList)){
                $button .= '<button class="delete btn btn-sm btn-danger m-1" data-id="'.encryptid($branchImb['id']).'">
                <i class="mdi mdi-delete"></i>
                </button>';
            }

            $result[] = array(
            "no"=>$key+1,
            "name"=>ucfirst($branchImb->company->name),
            "company_branch_name"=>ucfirst($branchImb->companyBranch->name),
            "branch_imb"=>ucfirst($branchImb->name),
            "action"=>$button
            );
        }
        return response()->json(['data'=>$result]);
    }

    //Show Data Of Branch Imd
    public function edit($id)
    {
        try {
            $data['branchImd'] = BranchImdName::where('id',decryptid($id))->first(['id','company_id','company_branch_id','name']);
            $data['companyBranch'] = CompanyBranch::where('company_id',$data['branchImd']['company_id'])->where('status',1)->get(['id','company_id','name']);
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

    //Remove Data Of Branch Imd
    public function destroy($id)
    {
        try {
            BranchImdName::where('id',decryptid($id))->update(['status' => 2]);
            $response = [
                'status' => true,
                'message' => "Branch Imd Data Deleted Successfully",
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

    /* Get Company Wise Company Branch Data */
    public function get_company_data(Request $request)
    {
        try{
            if(isset($request->company_id)){
                $data['company_branch'] = CompanyBranch::where("company_id",$request->company_id)->where('status',1)->get(["name", "id"]);
            }
            if(isset($request->branch_id)){
                $data['branch_imd'] = BranchImdName::where("company_branch_id",$request->branch_id)->where('status',1)->get(["name", "id"]);
            }
            $data['status']=true;
        }catch(\Throwable $e){
            $data['response'] = [
                'status' => false,
                'message' => "Something Went Wrong! Please Try Again.",
                'icon' => 'error',
                'redirect_url' => "/",
            ];
        }
        return response()->json($data);
    }

    /*Check Availability Of Branch Imb*/
    public function branch_imd_check(Request $request){
        if(isset($request) && $request->company_branch_name && $request->name && $request->id){
            $branch_imd = BranchImdName::where('company_branch_id',$request->company_branch_name)->where('name',$request->name)->where('id','!=',decryptid($request->id))->where('status',1)->first('name');
            return(!is_null($branch_imd))? true :false;
        }else{
            return false;
        }
    }
}
