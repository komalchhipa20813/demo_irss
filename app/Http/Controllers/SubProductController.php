<?php

namespace App\Http\Controllers;

use App\Models\{Product,SubProduct};
use Illuminate\Http\Request;

class SubProductController extends Controller {

    /* Dashboard of Sub Product */
    public function index() {
        $product = Product::where('status', 1)->get(['id', 'name']);
        return view('pages.settings.product.sub_product.sub-product', compact('product'));
    }

    /* Insert Or Update Sub Product Data */
    public function store(Request $request) {
        if (isset($request)) {
            $request->validate(
                [
                    'sub_product' => 'required',
                    'sub_product_name' => 'required',
                ]
            );
            try{
                SubProduct::updateOrCreate([
                    'id' => decryptid($request->id),
                ], [
                    'product_id' => $request->sub_product,
                    'name' => $request->sub_product_name,
                ]);
                $response = [
                    'status' => true,
                    'message' => 'Sub Product ' . (decryptid($request->id) == 0 ? 'Added' : 'Updated') . ' Successfully',
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

    /* Check The Sub Product Name In The Data */
    public function check_sub_product(Request $request) {
        if (isset($request) && $request->sub_product && $request->id &&  $request->name) {
            $subProduct = SubProduct::where('product_id', $request->product)->where('name', $request->name)->where('status', 1)->first(['id', 'name']);
            if (!is_null($subProduct) && $subProduct->id != decryptid($request->id)) {
                return true;
            } else {
                return false;
            }
        }
    }

    /* Sub Products Listing */
    public function listing() {
        $subProducts = SubProduct::where('status', 1)->with('product')->get(['id', 'product_id', 'name']);
        $result = [];
        $permissionList = permission();
        foreach ($subProducts as $key=>$subProduct) {
            $button = '';
            if (in_array("35", $permissionList)) {
                $button .= '<button class="edit_sub_product btn btn-sm btn-success m-1"  data-id="' . encryptid($subProduct['id']) . '">
                <i class="mdi mdi-square-edit-outline"></i>
                </button>';
            }
            if (in_array("36", $permissionList)) {
                $button .= '<button class="delete btn btn-sm btn-danger m-1" data-id="' . encryptid($subProduct['id']) . '">
                <i class="mdi mdi-delete"></i>
                </button>';
            }

            $result[] = array(
                'id' => $key + 1,
                'products' => ucfirst($subProduct->product->name),
                'subproducts_name' => ucfirst($subProduct->name),
                'action' => $button,
            );
        }
        return response()->json(['data'=>$result]);
    }

    //Edit Data Of Sub Product
    public function edit($id) {
        try{
            $data['subProduct'] = SubProduct::where('id', decryptid($id))->first(['id', 'product_id', 'name']);
            $data['product'] = Product::where('id',$data['subProduct']['product_id'])->where('status',1)->get(['id','name','policy_type']);
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

    /* Data Delete In The Sub Product */
    public function destroy($id) {

        try{
            SubProduct::where('id', decryptid($id))->update(['status' => 2]);
            $response = [
                'status' => true,
                'message' => "Sub Product Deleted Successfully",
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
