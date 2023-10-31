<?php

namespace App\Http\Controllers;

use App\Models\BusinessCategory;
use Illuminate\Http\Request;

class BusinessCategoryController extends Controller {

    /* Dashboard Of Business Category */
    public function index() {
        return view('pages.settings.general.business-category.business-category');
    }

    /* Check The Business Category Name In The Data*/
    public function business_category_check(Request $request) {
        if (isset($request) && $request->id && $request->name) {
            $businessCategory = BusinessCategory::where('name', $request->name)->where('status', 1)->first(['id', 'name']);
            if (!is_null($businessCategory) && $businessCategory->id != decryptid($request->id)) {
                return true;
            }
        } else {
            return false;
        }
    }

    /* Insert Or Update Business Category Data */
    public function store(Request $request) {
        $request->validate(
            [
                'name' => 'required',
            ]
        );
        try{
            BusinessCategory::updateOrCreate(
                [
                    'id' => decryptid($request->business_category_id),
                ],
                [
                    'name' => $request['name'],
                ]
            );
            $response = [
                'status' => true,
                'message' => 'Business Category ' . (decryptid($request->business_category_id) == 0 ? 'Added' : 'Updated ') . ' Successfully',
                'icon' => 'success',
            ];
        }catch (\Throwable $th) {
            $response = [
                'status' => false,
                'message' => "Something Went Wrong! Please Try Again.",
                'icon' => 'error',
            ];
        }
        return response($response);
    }

    /* Business Category Listing */
    public function listing() {
        $businessCategories = BusinessCategory::where('status', 1)->latest()->get(['id', 'name']);
        $result = [];
        $permissionList = permission();
        foreach ($businessCategories as $key=>$row) {
            $button = '';
            if (in_array("51", $permissionList)) {
                $button .= '<button class="business_category_edit btn btn-sm btn-success m-1" data-id="' . encryptid($row['id']) . '" >
                <i class="mdi mdi-square-edit-outline"></i>
                </button>';
            }
            if (in_array("52", $permissionList)) {
                $button .= '<button class="delete btn btn-sm btn-danger m-1" data-id="' . encryptid($row['id']) . '">
                <i class="mdi mdi-delete"></i>
                </button>';
            }
            $result[] = array(
                'id' => $key+1,
                'name' => ucfirst($row->name),
                'action' => $button
            );
        }
        return response()->json(['data'=>$result]);
    }

    /* Business Category Data Fetch */
    public function show($id) {
        try{
            $businessCategory = BusinessCategory::where('id', decryptid($id))->first(['id', 'name']);
            $response = [
                'data' => $businessCategory,
                'status' => true,
                'name' => $businessCategory->name,
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

    /* Data Delete In The Business Category */
    public function destroy($id) {
        try{
            BusinessCategory::where('id', decryptid($id))->update(['status' => 2]);
            $response = [
                'status' => true,
                'message' => "Business Category Delete Successfully",
                'icon' => 'success',
            ];
        }catch (\Throwable $th) {
            $response = [
                'status' => false,
                'message' => "Something Went Wrong! Please Try Again.",
                'icon' => 'error',
            ];
        }
        return response($response);
    }
}
