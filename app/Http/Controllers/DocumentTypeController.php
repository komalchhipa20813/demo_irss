<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use Illuminate\Http\Request;

class DocumentTypeController extends Controller {

    /* Display Dashboard Of Document Type */
    public function index() {
        return view('pages.settings.general.document_type.document_type');
    }

    /* Insert Or Update Document Type */
    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
        ]);
        try {
            DocumentType::updateOrCreate([
                'id' => decryptid($request->document_type_id),
            ],
            [
                'name' => $request['name'],
            ]);
            $response = [
                'status' => true,
                'message' => 'Document Type ' . (decryptid($request->document_type_id) == 0 ? 'Added' : 'Updated') . ' Successfully',
                'icon' => 'success',
            ];
        } catch (\Throwable $th) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
        }
        return response($response);
    }

    /* Checking Availability of Document Type */
    public function checkDocumenTtype(Request $request) {
        if(isset($request) && $request->id && $request->name){
            $document_type = DocumentType::where('name', $request->name)->where('id','!=',decryptid($request->id))->where('status', 1)->first('name');
            return !is_null($document_type) ? true: false ;
        }else{
            return false;
        }
    }

    /* Listing Of Document Type */
    public function listing() {
        $document_types = DocumentType::where('status', 1)->latest()->get(['id','name']);
        $result = [];
        $permissionList = permission();
        foreach ($document_types as $key=>$document_type) {
            $button = '';
            if (in_array("15", $permissionList)) {
                $button .= '<button class="edit_document_type btn btn-sm btn-success m-1" data-id="' . encryptid($document_type['id']) . '" >
                <i class="mdi mdi-square-edit-outline"></i></button>';
            }
            if (in_array("16", $permissionList)) {
                $button .= '<button class="delete btn btn-sm btn-danger m-1" data-id="' . encryptid($document_type['id']) . '">
                <i class="mdi mdi-delete"></i>
                </button>';
            }
            $result[] = array(
                "no" => $key+1,
                "name" => ucfirst($document_type['name']),
                "action" => $button
            );
        }
        return response(['data'=>$result]);
    }

    /* Fetch Document Type Data */
    public function show($id) {
        try {
            $document_type = DocumentType::where('id', decryptid($id))->first('name');
            $response = [
                'data' => $document_type,
                'status'=>true,
            ];
        } catch (\Throwable $th) {
            $response = [
                'status' => false,
                'message' => "Something Went Wrong! Please Try Again.",
                'icon' => 'error',
            ];
        }
        echo json_encode($response);
    }

    /* Delete Document Type Data */
    public function destroy($id) {
        try {
            DocumentType::where('id', decryptid($id))->update(['status' => 2]);
            $response = [
                'status' => true,
                'message' => "Document Type Deleted Successfully",
                'icon' => 'success',
            ];
        } catch (\Throwable $th) {
            $response = [
                'status' => false,
                'message' => "Something Went Wrong! Please Try Again.",
                'icon' => 'error',
            ];
        }
        return response($response);
    }
}
