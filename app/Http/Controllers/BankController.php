<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Bank};

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.settings.general.bank.bank');
    }

    //Listing Data Of Bank
    public function listing(){
        $data['bankData']= Bank::where('status',1)->get(['id','name']);
        $result = [];
        $permissionList = permission();
        foreach ($data['bankData'] as $key=> $bank) {
            $button = '';
            if(in_array("99", $permissionList)){
                $button .= '<button class="edit_bank btn btn-sm btn-success m-1"  data-id="'.encryptid($bank['id']).'">
                <i class="mdi mdi-square-edit-outline"></i>
                </button>';
            }
            if(in_array("100", $permissionList)){
                $button .= '<button class="delete btn btn-sm btn-danger m-1" data-id="'.encryptid($bank['id']).'">
                <i class="mdi mdi-delete"></i>
                </button>';
            }

            $result[] = array(
            "no"=>$key+1,
            "name"=>ucfirst($bank->name),
            "action"=>$button
            );
        }
        return response()->json(['data'=>$result]);
    }

   //Listing Data Of Bank
    public function data(){
        $bankdata= Bank::where('status',1)->get(['id','name']);
        return json_encode($bankdata);
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
            ]
        );
        try {
            Bank::updateOrCreate([
                'id' => decryptid($request->id),
            ],[
                'name' => $request->name,
            ]);

            $response = [
                'status' => true,
                'message' => 'Bank Data '.(decryptid($request->id) ==0 ? 'Added' : 'Updated').' Successfully',
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
            $data['bank'] = Bank::where('id',decryptid($id))->first('name');
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
            Bank::where('id',decryptid($id))->update($update);;
            $response = [
                'status' => true,
                'message' => "Bank Data Deleted Successfully",
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

    /*Check Availability Of Bank*/
     public function bank_check(Request $request){
        if(isset($request) && $request->name && $request->id){
        $bank = Bank::where('name',$request->name)->where('id','!=',decryptid($request->id))->where('status',1)->first('name');
        return(!is_null($bank))? true :false;
        }else{
            return false;
        }
    }
}
