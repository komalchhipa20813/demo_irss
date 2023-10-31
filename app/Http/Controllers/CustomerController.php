<?php

namespace App\Http\Controllers;

use App\Exports\ExportCustomer;
use Illuminate\Http\Request;
use App\Models\{Customer, Settings};
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query=Customer::where('status','!=',2)->WhereRaw("CONCAT(first_name, ' ',last_name,' ') like '%AAIFUEL PETROLEUM%'");
        dd($query->get());
       
        $data['customers']=Customer::where('status',1)->skip(50742)
        ->take(10)->get(['id','prefix','first_name','middle_name','last_name']);
        return view('pages.irss-master.customer.index',compact('data'));
    }

    /* listing of employee */
    public function listing(Request $request)
    {
        $search = $request->input('search.value');
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
        $totalRecords = Customer::where('status','!=',2)->count();
        $query=Customer::where('status','!=',2)->latest();

        if (!empty($request->customer_code)) {
                $query->where('customer_code', $request->customer_code);
        }

        if (!empty($request->adharcard_number)) {
                $query->where('adharcard_number', $request->adharcard_number);
        }

        if (!empty($request->pancard_number)) {
                $query->where('pancard_number', $request->pancard_number);
        }

        if(!empty($search))
		{
			$query->where('first_name' , 'LIKE' , '%'.$search.'%');
			$query->orwhere('last_name' , 'LIKE' , '%'.$search.'%');
			$query->orwhere('middle_name' , 'LIKE' , '%'.$search.'%');

			
		}
        
        if (!empty($request->customer_id)) {

       

            // $query->where(DB::raw('CONCAT(first_name, " ", middle_name," ",last_name)'), 'LIKE', '%' . $request->customer_id . '%');
            // ->orWhere('first_name', 'LIKE', "%{$request->customer_id}%")
            // ->orWhere('middle_name', 'LIKE', "%{$request->customer_id}%")
            // ->orWhere('last_name', 'LIKE', "%{$request->customer_id}%");


            // $query->where(function($query)  use ($request) {
            //     $query->whereRaw("CONCAT(first_name,last_name) like '%' . $request->customer_id . '%'")
            //     ->orWhere('middle_name', 'like', '%' . $request->customer_id . '%')
            //     ->orWhere('last_name', 'like', '%' . $request->customer_id . '%');
            // });

                
                // $query->where('id', decryptid($request->customer_id));
        }
        $totalRecordswithFilter = $query->count();
        $customers = $query
        ->skip($start)
        ->take($rowperpage)
        ->get(); 
        $records = [];
        $permissionList = isset(Auth::user()->id)?permission():'';
        foreach ($customers as $key => $row) {
            $button = '';

            if(isset(Auth::user()->id)&&in_array("83", $permissionList)){
                $button .= '<a href="'.route('customer.edit',encryptid($row['id'])).'"><button class=" btn btn-sm btn-success m-1">
                <i class="mdi mdi-square-edit-outline"></i>
                </button></a>';
            }
            if(isset(Auth::user()->id)&&in_array("84", $permissionList)){
                $button .= '<button class="delete btn btn-sm btn-danger m-1" data-id="'.encryptid($row['id']).'">
                <i class="mdi mdi-delete"></i>
                </button>';
            }

            //Customers Data

            $records[] = array(
                'no' => $key + 1,
                'customer_code' => $row->customer_code,
                'customer_name' => $row->prefix.' '.ucwords($row->first_name).' '.ucwords($row->last_name),
                'address' => $row->address,
                'adharcard_number' => (isset($row->adharcard_number) ? $row->adharcard_number : '' ),
                'pancard_number' => (isset($row->pancard_number) ? $row->pancard_number : '' ),
                'action' => $button,
            );
        }
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $records
         );
        return response($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try{

            $data['customer']=null;
            return view('pages.irss-master.customer.create',compact('data'));
        }catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
            return response($response);
        }
    }

    /* checking customer-adharcard for availability */
    public function customer_check(Request $request) {
        if(isset($request) && $request->adharcard_number && $request->id)
        {
            $customer = Customer::where('adharcard_number', $request->adharcard_number)->where('id','!=',decryptid($request->id))->where('status','=',1)->first('id');

            return(!is_null($customer))?true:false;
        }else{
            return false;
        }
    }

      /* Checking Customer Pan Card Check for availability */
      public function customer_pan_card_check(Request $request) {
        if(isset($request) && $request->pan_card && $request->id)
        {
            $customer = Customer::where('pancard_number', $request->pan_card)->where('id','!=',decryptid($request->id))->where('status','=',1)->first('id');

            return(!is_null($customer))?true:false;
        }else{
            return false;
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

    
         try{
             $request->validate(
                [
                    'prefix' => 'required',
                    'address' => 'required',

                ]
            );

            if(decryptid($request->customer_id)==0){
                $customer_code=Settings::where('key','customer_code')->first()->value;
                Settings::where('key','customer_code')->update(['value'=>$customer_code+1]);
            }else{
                $customer_code=Customer::where('id',decryptid($request->customer_id))->first()->customer_code;
            }
            $result=Customer::updateOrCreate(
                [
                    'id' => decryptid($request->customer_id),
                ],
                [
                    'customer_code' => $customer_code,
                    'prefix' => $request->prefix,
                    'first_name' => ($request->first_name)?$request->first_name: $request->company_name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'mobile_no' => $request->mobile_no,
                    'phone_no' => (isset($request->phone_no))? $request->phone_no:null,
                    'address' => $request->address,
                    'email'=>(isset($request->email))? $request->email:null,
                    'adharcard_number'=>(isset($request->aadhaarcard_number))? $request->aadhaarcard_number:null,
                    'pancard_number'=>(isset($request->pancard_number))? $request->pancard_number:null,
                    'gst_number'=>(isset($request->gst_number))? $request->gst_number:null,
                ]

            );
            if($result)
            {
                    $response = [
                    'customer_code'=>$customer_code,
                    'id'=>$result->id,
                    'status' => true,
                    'message' => 'Customer ' . $customer_code.(decryptid($request->customer_id) == '0' ? ' Created' : ' Updated') . ' Successfully',
                    'icon' => 'success',
                    'redirect_url' => "customer",
                    'is_customer' =>isset($request->customer_from_policy)?true:false
                ];
            }
            else
            {
                $response = [
                'status' => false,
                'message' =>'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
                'redirect_url' => "customer",
            ];
            }

        }catch (\Throwable $e) {
            dd($e); 
            $response = [
                'status' => false,
                'message' =>'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
                'redirect_url' => "customer",
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
        try{
             $data['customer']=Customer::where('status',1)->find(decryptid($id));;
            return view('pages.irss-master.customer.create',compact('data'));
        }catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
            return response($response);
        }
    }

    public function get_customer(Request $request)
    {
        try{
             $customer=Customer::where('status',1)->find(decryptid($request->id));;
            $response = [
                'customer'=>$customer,
                'status' => true,
                'message' => '',
            ];
            return response($response);
        }catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
            return response($response);
        }
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
            $update = ['status'=>2];
            Customer::where('id', decryptid($id))->update($update);
            $response = [
                'status' => true,
                'message' => "Customer Data Deleted Successfully",
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
    public function get_data()
    {
        $customers=Customer::where('status',1)->latest()->get(['id','prefix','first_name','middle_name','last_name','customer_code']);
        return response()->json($customers);
    }

    public function export() 
    {
        return Excel::download(new ExportCustomer, 'customer.xlsx');
    }
    public function get_customers(Request $request)
    {
        try{
            $search = $request->search;

            if($search == ''){
               $customers = Customer::where('status',1)->get(['id','first_name','last_name','middle_name','prefix','customer_code']);
            }else{
               $customers = Customer::where('status',1)->where('first_name', 'LIKE', '%'.$search.'%')
               ->orWhere('last_name', 'LIKE', '%'.$search.'%')
               ->orWhere('middle_name', 'LIKE', '%'.$search.'%')
               ->orWhere('customer_code', 'LIKE', '%'.$search.'%')
               ->get(['id','first_name','last_name','middle_name','prefix','customer_code']);
            }
      
            $response = array();
            foreach($customers as $customer){
               $response[] = array(
                    "id"=>$customer->id,
                    "text"=>ucfirst($customer->customer_code.' '.$customer->prefix.' '.$customer->first_name.' '.$customer->middle_name.' '.$customer->last_name)
               );
            }
            return response()->json($response); 
        }catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
            return response($response);
        }
    }
}
