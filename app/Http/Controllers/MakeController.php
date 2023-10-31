<?php

namespace App\Http\Controllers;

use App\Models\{Make,Product,ProductModel,ProductVariant};
use Illuminate\Http\Request;

class MakeController extends Controller
{
    /* dashboard of make */
    public function index()
    {
        $productList =  Product::where('policy_type',1)->where('status',1)->get();
        return view('pages.settings.motor-policy-settings.product-make.make',compact('productList'));
    }

    /* store data to database */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'product'=>'required',
            ]
        );
        try {
            Make::updateOrCreate([
                'id' => decryptid($request->id),
            ],[
                'name' => $request->name,
                'product_id'=>$request->product,
                'status'=>1,
            ]);

            $response = [
                'status' => true,
                'message' => 'Product Make Data '.(decryptid($request->id) == 0 ? 'Added' : 'Updated').' Successfully',
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

    /* listing od make */
    public function listing(){
        $data['makeData']= Make::where('status',1)->with('product')->get();
        $result = [];
        $permissionList = permission();
        foreach ($data['makeData'] as $key=> $make) {
            $button = '';
            if(in_array("87", $permissionList)){
                $button .= '<button class="edit_make btn btn-sm btn-success m-1"  data-id="'.encryptid($make['id']).'">
                <i class="mdi mdi-square-edit-outline"></i>
                </button>';
            }
            if(in_array("88", $permissionList)){
                $button .= '<button class="delete btn btn-sm btn-danger m-1" data-id="'.encryptid($make['id']).'">
                <i class="mdi mdi-delete"></i>
                </button>';
            }

            $result[] = array(
            "no"=>$key+1,
            "name"=>ucfirst($make->name),
            "product_name"=>ucfirst($make['product']['name']),
            "action"=>$button
            );
        }
        return response()->json(['data'=>$result]);
    }

    /* fetch data */
    public function edit($id)
    {
        try {
            $data['make'] = Make::where('id',decryptid($id))->first(['name','product_id']);
            $response = [
                'data'=>$data,
                'status'=>true,
            ];

        }catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => "Something Went Wrong! aPlease Try Again.",
                'icon' => 'error',
            ];
        }

        return response($response);
    }

    /* delete make */
    public function destroy($id)
    {
        try {
            $update['status'] = 2;
            Make::where('id',decryptid($id))->update($update);
            ProductModel::where('make_id',decryptid($id))->update($update);
            $model_ids=ProductModel::where('make_id', decryptid($id))->pluck('id')->toArray();
            ProductVariant::whereIn('model_id',$model_ids)->update($update);
            $response = [
                'status' => true,
                'message' => "Make Deleted Successfully",
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

    /*Check Availability Of make*/
     public function make_check(Request $request){
        if(isset($request) && $request->product_id && $request->name && $request->id){
         $make = Make::where('product_id',$request->product_id)->where('name',$request->name)->where('id','!=',decryptid($request->id))->where('status',1)->first('name');
         return(!is_null($make))? true :false;
         }else{
            return false;
         }
     }

     /* Get State Wise Make Data */
    public function get_make_name(Request $request)
    {
        $data['make'] = Make::where("product_id",$request->product)->where('status',1)->get(["name", "id"]);
        return response()->json($data);
    }
}
