<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Product,ProductType};

class ProductTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        $product = Product::where('status', 1)->get(['id', 'name']);
        return view('pages.settings.product.product-type.product_type', compact('product'));
    }

     /* Sub Products Listing */
    public function listing() {
        $types = ProductType::where('status', 1)->with('product')->get(['id', 'product_id', 'type']);
        $result = [];
        foreach ($types as $key=>$type) {
            $button = '';
            if (in_array("107", permission())) {
                $button .= '<button class="edit_product_type btn btn-sm btn-success m-1"  data-id="' . encryptid($type['id']) . '">
                <i class="mdi mdi-square-edit-outline"></i>
                </button>';
            }
            if (in_array("108", permission())) {
                $button .= '<button class="delete btn btn-sm btn-danger m-1" data-id="' . encryptid($type['id']) . '">
                <i class="mdi mdi-delete"></i>
                </button>';
            }

            $result[] = array(
                'id' => $key + 1,
                'products' => ucfirst($type['product']['name']),
                'type' => ucfirst($type['type']),
                'action' => $button,
            );
        }
        return response()->json(array('data'=>$result));
    }


    /* Check The Sub Product Name In The Data */
    public function check_product_type(Request $request) {
        if (isset($request) && $request->product && $request->id &&  $request->type) {
            $product_type = ProductType::where('product_id', $request->product)->where('type', $request->type)->where('status', 1)->first(['id', 'type']);
            if (!is_null($product_type) && $product_type->id != decryptid($request->id)) {
                return true;
            } else {
                return false;
            }
        }
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       if (isset($request)) {
            $request->validate(
                [
                    'product' => 'required',
                    'type' => 'required',
                ]
            );
            try{
                ProductType::updateOrCreate([
                    'id' => decryptid($request->id),
                ], [
                    'product_id' => $request->product,
                    'type' => $request->type,
                ]);
                $response = [
                    'status' => true,
                    'message' => 'Product Type ' . (decryptid($request->id) == 0 ? 'Added' : 'Updated') . ' Successfully',
                    'icon' => 'success',
                ];
            }catch(\Throwable $e){
                $response = [
                    'status' => false,
                    'message' => "Something went wrong! please try again.",
                    'icon' => 'error',
                ];
            }
            return response()->json($response);
        }
    }

    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            $data['product_type'] = ProductType::where('id', decryptid($id))->first(['id', 'product_id', 'type']);
            $data['product'] = Product::where('id',$data['product_type']['product_id'])->where('status',1)->get(['id','name','policy_type']);

            $response = [
                'data' => $data,
                'status' => true,
            ];
        }catch(\Throwable $e){
            $response = [
                'status' => false,
                'message' => "Something Went Wrong! Please Try Again.",
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
        try{
            ProductType::where('id', decryptid($id))->update(['status' => 2]);
            $response = [
                'status' => true,
                'message' => "Product Type Deleted Successfully",
                'icon' => 'success',
            ];
        }catch(\Throwable $e){
            $response = [
                'status' => false,
                'message' => "Something Went Wrong! Please Try Again.",
                'icon' => 'error',
            ];
        }
        return response($response);
    }

     /* Get Policy Wise Product Data */
     public function get_product_name(Request $request)
     {
         $data['product'] = Product::where("policy_type",$request->policy_type)->where('status',1)->get(["name", "id"]);
         return response()->json($data);
     }
}
