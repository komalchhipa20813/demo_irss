<?php

namespace App\Http\Controllers;

use App\Models\{Company, Make, Product,ProductCompany, ProductModel, ProductType, ProductVariant, SubProduct};
use Illuminate\Http\Request;

class ProductController extends Controller {

    /* Dashboard Of Product */
    public function index() {
        $product = Product::where('status', 1)->get(['id', 'name']);
        return view('pages.settings.product.product.product');
    }

    /* Create Products And Fetch Companies */
    public function create() {
        $data['companies'] = Company::where('status', 1)->get(['id', 'name']);
        $data['products'] = null;
        $data['p_id'] = null;
        return view('pages.settings.product.product.product-add', compact('data'));
    }

    /* Product data Listing */
    public function listing() {
        $products = Product::where('status', 1)->get(['id', 'name','policy_type']);
        $result = [];
        $permissionList = permission();
        foreach ($products as $key=>$row) {
            $button = '';
            if (in_array("29", $permissionList)) {
                $button .= '<a href="' . route('product.show', encryptid($row['id'])) . '"><button class="btn btn-sm btn-success m-1"  data-id="' . encryptid($row['id']) . '" >
                <i class="mdi mdi-view-module"></i>
                </button></a>';
            }
            if (in_array("31", $permissionList)) {
                $button .= '<a href="' . route('product.edit', encryptid($row['id'])) . '"><button class=" btn btn-sm btn-success m-1">
                <i class="mdi mdi-square-edit-outline"></i>
                </button></a>';
            }
            if (in_array("32", $permissionList)) {
                $button .= '<button class="delete btn btn-sm btn-danger m-1" data-id="' . encryptid($row['id']) . '">
                <i class="mdi mdi-delete"></i>
                </button>';
            }
            $policy_type=($row->policy_type==1)?'Motor Policy':(($row->policy_type==2)?'Health Policy':'SME Policy');
            $result[] = array(
                'id' => $key + 1,
                'policy type' =>$policy_type,
                'name' => ucfirst($row->name),
                'action' => $button
            );
        }
        return response()->json(['data'=>$result]);
    }

    /* Insert Or Update Product Data */
    public function store(Request $request) {
        $request->validate(
            [
                'name' => 'required',
                'policy_type' => 'required',
                'companies' => 'required|array',
            ],
        );

        try{
            $product=Product::updateOrCreate(
                [
                    'id' => decryptid($request['product_id']),
                ],
                [
                    'name' => $request->name,
                    'policy_type' => $request->policy_type,

                ]
            );
            ProductCompany::where('product_id', decryptid($request['product_id']))->delete();
            foreach ($request['companies'] as $company) {
                $companies[] = [
                    "product_id" => $product->id,
                    "company_id" => $company
                ];
            }
            $x=ProductCompany::insert($companies);
            if ($x) {
                $response = [
                    'status' => true,
                    'message' => 'Product ' . (decryptid($request['product_id']) == 0 ? 'Added' : 'Updated ') . ' Successfully',
                    'icon' => 'success',
                    'redirect_url' => "product",
                ];
            } else {
                $product->delete();
                $response = [
                    'status' => false,
                    'message' => 'Something Went Wrong! Please Try Again.',
                    'icon' => 'error',
                ];
            }
        }catch(\Throwable $e){
            $response = [
                'status' => false,
                'message' => "Something Went Wrong! Please Try Again.",
                'icon' => 'error',
                'redirect_url' => "product/create",
            ];
        }
        return response()->json($response);
    }

    /* Check The Product Name In The Data */
    public function product_check(Request $request) {
        if (isset($request) && $request->id && $request->name && $request->policy_type) {
            $product = Product::where('name',$request->name)->where('policy_type',$request->policy_type)->where('status', 1)->where('id','!=',decryptid($request->id))->first(['id']);
            return !is_null($product)?true:false;
        } else {
            return false;
        }
    }

    /* Fetch Product Data */
    public function show($id) {
        try {
            $product = Product::where('id', decryptid($id))->with('product_company')->first(['id', 'name']);
            if (!empty($product)) {
                $p_id = [];
                foreach ($product->product_company as $key=>$products_company) {
                    $company = $products_company->load('company_product')->company_product;
                    $p_id[$key] = $company->name;
                }
                $name = $product['name'];
                $data = array("name" => $name, "p_id" => $p_id);
            } else {
                return redirect(route('product.index'));
            }
            return view('pages.settings.product.product.product-show', compact('data'));
        }catch(\Throwable $e){
            $response = [
                'status' => false,
                'message' => "Something Went Wrong! Please Try Again.",
                'icon' => 'error',
                'redirect_url' => "product/create",
            ];
        }
    }

    /* Edit Product Data */
    public function edit($id) {
        try {
            $product = Product::where('id', decryptid($id))->with('product_company')->first(['id', 'name','policy_type']);
            $data['products'] = $product;
            $data['companies'] = Company::where('status', 1)->get(['id', 'name']);
            if (!empty($product)) {
                $p_id = [];
                foreach ($product->product_company as $key=>$company) {
                    $p_id[$key] = $company->company_id;
                }
                $title = $product->name;
                $data['p_id'] = array("p_id" => $p_id);
                return view('pages.settings.product.product.product-add', compact('data'));
            } else {
                return view('pages.settings.product.product.product');
            }
        }catch(\Throwable $e){
            $response = [
                'status' => false,
                'message' => "Something Went Wrong! Please Try Again.",
                'icon' => 'error',
                'redirect_url' => "product/create",
            ];
            return response($response);
        }
    }

    /* Data Delete In The Product */
    public function destroy($id) {
        try{
            $update = ['status'=>2];
            Product::where('id', decryptid($id))->update($update);
            SubProduct::where('product_id', decryptid($id))->update($update);
            Make::where('product_id', decryptid($id))->update($update);
            $getMake = Make::where('product_id', decryptid($id))->pluck('id')->toArray();
            $productData =  ProductModel::whereIn('make_id',$getMake);
            $productData->update($update);
            $productID = $productData->pluck('id')->toArray();
            ProductVariant::whereIn('model_id',$productID)->update($update);

                $response = [
                    'status' => true,
                    'message' => "Product Deleted Successfully",
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

    /* Get Product Wise Company & Sub-product Data */
    public function get_data(Request $request)
    {
        try{
            $company_id=ProductCompany::where('product_id',$request->product_id)->pluck('company_id');
            $data['company'] = Company::whereIn('id',$company_id)->where('status',1)->get(["name", "id"]);
            $data['sub_product']=SubProduct::where('product_id',$request->product_id)->where('status',1)->get(['name','id']);
            $data['product_type'] = ProductType::where("product_id",$request->product_id)->where('status',1)->get(["type", "id"]);
            $data['product_make']=Make::where("product_id",$request->product_id)->where('status',1)->get(["name", "id"]);
            $data['status']=true;
        }catch(\Throwable $e){
            $data['response'] = [
                'status' => false,
                'message' => "Something Went Wrong! Please Try Again.",
                'icon' => 'error',
                'redirect_url' => "product/create",
            ];
        }
        return response()->json($data);
    }
}
