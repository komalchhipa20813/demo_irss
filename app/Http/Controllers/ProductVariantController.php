<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{ProductModel,Product,ProductVariant,Make};

class ProductVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productList =  Product::where('policy_type',1)->where('status',1)->get();
        return view('pages.settings.motor-policy-settings.product-variant.variant',compact('productList'));
    }

     /* listing of Variant */
    public function listing(){
        $data['variantData']= ProductVariant::where('status',1)->with('model')->get();
        $result =[];
        $permissionList = permission();
        foreach ($data['variantData'] as $key=> $variant) {
            $button = '';
            if(in_array("95", $permissionList)){
                $button .= '<button class="edit_variant btn btn-sm btn-success m-1"  data-id="'.encryptid($variant['id']).'">
                <i class="mdi mdi-square-edit-outline"></i>
                </button>';
            }
            if(in_array("96", $permissionList)){
                $button .= '<button class="delete btn btn-sm btn-danger m-1" data-id="'.encryptid($variant['id']).'">
                <i class="mdi mdi-delete"></i>
                </button>';
            }

            $result[] = array(
            "no"=>$key+1,
            "name"=>ucfirst($variant->name),
            "model_name"=>ucfirst($variant['model']['name']),
            "make_name"=>ucfirst($variant['model']['make']['name']),
            "product_name"=>ucfirst($variant['model']['make']['product']['name']),
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
                'model_id'=>'required',
            ]
        );
        try {
            ProductVariant::updateOrCreate([
                'id' => decryptid($request->id),
            ],[
                'name' => $request->name,
                'model_id'=>$request->model_id,
                'status'=>1,
            ]);

            $response = [
                'status' => true,
                'message' => 'Product variant Data '.(decryptid($request->id) == 0 ? 'Added' : 'Updated').' Successfully',
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
            $data['variant'] = ProductVariant::where('id',decryptid($id))->with('model')->first();
            $data['model']=ProductModel::where('make_id',$data['variant']['model']['make_id'])->where('status',1)->get();
            $data['make']=Make::where('product_id',$data['variant']['model']['make']['product_id'])->where('status',1)->get(['id','name','product_id']);
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
            ProductVariant::where('id',decryptid($id))->update($update);
            $response = [
                'status' => true,
                'message' => "Product Variant Data Deleted Successfully",
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

    /*Check Availability Of Variant*/
     public function variant_check(Request $request){
        if(isset($request) && $request->model_id && $request->name && $request->id){
         $variant = ProductVariant::where('model_id',$request->model_id)->where('name',$request->name)->where('id','!=',decryptid($request->id))->where('status',1)->first('name');
         return(!is_null($variant))? true :false;
         }else{
            return false;
         }
     }

      /* Get State Wise Variant Data */
    public function get_variant_name(Request $request)
    {
        $data['variant'] = ProductVariant::where("model_id",$request->model_id)->where('status',1)->get(["name", "id"]);
        return response()->json($data);
    }
}
