<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{ProductModel,Product,Make,ProductVariant};

class ProductModelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productList =  Product::where('policy_type',1)->where('status',1)->get();
        return view('pages.settings.motor-policy-settings.product-model.model',compact('productList'));
    }


    /* listing od model */
    public function listing(){
        $data['modelData']= ProductModel::where('status',1)->with('make')->get();
        $result =[];
        $permissionList = permission();
        foreach ($data['modelData'] as $key=> $model) {
            $button = '';
            if(in_array("91", $permissionList)){
                $button .= '<button class="edit_model btn btn-sm btn-success m-1"  data-id="'.encryptid($model['id']).'">
                <i class="mdi mdi-square-edit-outline"></i>
                </button>';
            }
            if(in_array("92", $permissionList)){
                $button .= '<button class="delete btn btn-sm btn-danger m-1" data-id="'.encryptid($model['id']).'">
                <i class="mdi mdi-delete"></i>
                </button>';
            }

            $result[] = array(
            "no"=>$key+1,
            "name"=>ucfirst($model->name),
            "make_name"=>ucfirst($model['make']['name']),
            "product_name"=>ucfirst($model['make']['product']['name']),
            "action"=>$button
            );
        }

        return response()->json(['data'=>$result]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $request->validate(
            [
                'name' => 'required',
                'make_id'=>'required',
                'product'=>'required',
            ]
        );
        try {
            $a= ProductModel::updateOrCreate([
                'id' => decryptid($request->id),
            ],[
                'name' => $request->name,
                'make_id'=>$request->make_id,
                'status'=>1,
            ]);

            $response = [
                'status' => true,
                'message' => 'Product Model Data '.(decryptid($request->id) == 0 ? 'Added' : 'Updated').' Successfully',
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



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $data['model'] = ProductModel::where('id',decryptid($id))->with('make')->first();
            $data['make']=Make::where('product_id',$data['model']['make']['product_id'])->where('status',1)->get(['id','name','product_id']);
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


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $update['status'] = 2;
            ProductModel::where('id',decryptid($id))->update($update);
            ProductVariant::where('model_id',decryptid($id))->update($update);
            $response = [
                'status' => true,
                'message' => "Product Model Data Deleted Successfully",
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

    /*Check Availability Of model*/
     public function model_check(Request $request){
        if(isset($request) && $request->product_id && $request->make_id && $request->name && $request->id){
         $model = ProductModel::where('make_id',$request->make_id)->where('name',$request->name)->where('id','!=',decryptid($request->id))->where('status',1)->first('name');
         return(!is_null($model))? true :false;
         }else{
            return false;
         }
     }

     /* Get State Wise Make Data */
    public function get_model_name(Request $request)
    {
        $data['model'] = ProductModel::where("make_id",$request->make_id)->where('status',1)->get(["name", "id"]);
        return response()->json($data);
    }


}
