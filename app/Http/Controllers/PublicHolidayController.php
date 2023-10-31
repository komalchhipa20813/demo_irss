<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PublicHoliday;

use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class PublicHolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return view('pages.settings.public-holiday.holiday');
    }


    //Listing Data Of Holiday
    public function listing(){
        $permissionList = permission();
        $query=PublicHoliday::select('*');

        if(in_array("67", $permissionList)){
            $query->where('status','!=',2);
        }
        else
        {
            $query->where('status',1);
        }

        $data['holidayData']= $query->get(['id','title','date','holiday_type','status']);
        $result = [];

        foreach ($data['holidayData'] as $key=> $holiday) {
            $button = '';
            if(in_array("67", $permissionList)){
                $button .= '<button class="edit_holiday btn btn-sm btn-success m-1"  data-id="'.encryptid($holiday['id']).'">
                <i class="mdi mdi-square-edit-outline"></i>
                </button>';
            }
            if(in_array("68", $permissionList)){
                $button .= '<button class="delete btn btn-sm btn-danger m-1" data-id="'.encryptid($holiday['id']).'">
                <i class="mdi mdi-delete"></i>
                </button>';
            }


            $result[] = array(
                "no" => ($key + 1),
                "title"=>ucfirst($holiday->title),
                "date"=>date('d-M-Y',strtotime($holiday->date)),
                "holiday_type"=>$holiday->holiday_type,
                "action"=>$button,
            );
        }

        return response()->json(['data'=>$result]);
    }

    /* change status */
    public function ajaxchangestatus(Request $request) {
        $holiday = PublicHoliday::where('id', decryptid($request->id))->first();
        if (is_null($holiday)) {
            $response = [
                'status' => false,
                'message' => "Record not found",
                'icon' => 'error',
            ];
        } else {
            $update['status'] = $request->is_active;
            $result = PublicHoliday::where('id', decryptid($request->id))->update($update);

            if ($result) {
                $response = [
                    'status' => true,
                    'message' => 'Status Updated Successfully',
                    'icon' => 'success',
                ];
            } else {
                $response = [
                    'status' => false,
                    'message' => "error in updating",
                    'icon' => 'error',
                ];
            }
        }
        return response($response);
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
                'title' => 'required',
                'date' => 'required',
                'holiday_type' => 'required',
            ]
        );
        $status = ($request->has('status')) ? true : false;
        try {
            PublicHoliday::updateOrCreate([
                'id' => decryptid($request->id),
            ],[
                'title' => $request->title,
                'date' => $request->date,
                'holiday_type' => $request->holiday_type,
                'status'=>$status
            ]);

            $response = [
                'status' => true,
                'message' => 'Public Holiday Data '.(decryptid($request->id) ==0 ? 'Added' : 'Updated').' Successfully',
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
            $data['holiday'] = PublicHoliday::where('id',decryptid($id))->first(['id','title','date','holiday_type','status']);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
            PublicHoliday::where('id',decryptid($id))->update($update);
            $response = [
                'status' => true,
                'message' => "Public Holiday Data Deleted Successfully",
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

     /*Check Availability Of Country*/
     public function holiday_check(Request $request){
        if(isset($request) && $request->title && $request->id){
        $holiday = PublicHoliday::where('title',$request->title)->where('id','!=',decryptid($request->id))->where('status',1)->first('title');
        return(!is_null($holiday))? true :false;
        }else{
            return false;
        }
    }

     public function generate_pdf(Request $request)
    {
        $data['holidays']=PublicHoliday::where('status',1)->get();
        $pdf = FacadePdf::loadView('pages.settings.public-holiday.pdf',compact('data'));
        $pdf->setPaper('A4', 'portrait');
        $path = public_path() . '/storage/holiday/';
        $fileName =  time().'.'. 'pdf' ;
        if(!is_dir($path)){
            mkdir($path);
        }
        $pdf->save($path . $fileName);
        $pdf_url =  public_path() . '/storage/holiday/'.$fileName;
        $headers = array('Content-Type'=> 'application/pdf');
        return response()->download($pdf_url);
    }
}
