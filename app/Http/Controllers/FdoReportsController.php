<?php

namespace App\Http\Controllers;

use App\Models\{Fdo};
use App\Exports\FdoDetailExport;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class FdoReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return view('pages.reports.fdo-details.index');
    }

     public function export_fdo_detail(Request $request)
    {
         $fdos=Fdo::where('status','!=',2)->with('bank');

            
            if ($request->status != '') {
                $status=($request->status == 'Active')? 1 : 0;
                $fdos->where('status', $status);
            }

            if (isset($request->from_date) && isset($request->end_date)) {
                $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
                $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                $fdos->whereBetween('created_at', [$from_date, $to_date]);
            }
            // $fdos = $fdos->latest()->get(['code','prefix','first_name','middle_name','last_name','account_number','bank_id','ifsc_code','pancard_number']);
             $fdos = $fdos->latest()->get();

        if($request->export_type == 'Excel')
        {
             if(!empty($fdos) && sizeof($fdos) != 0)
            {
                    $myFile= $this->export_excel_fdo_data($request);
                    $response = array(
                            'data'=>$request->all(),
                            'status' => true,
                            'type'=>'Excel',
                            'name' => "FOD-Details-Report.xlsx",
                            'file' => "data:application/vnd.ms-excel;base64," . base64_encode($myFile),
                        );
                     return response()->json($response);
             }
             else
             {
                 $response = array(
                            'status' => false,
                            'message' =>'No Data Avilable.',
                            'icon' => 'error',
                            'redirect_url' => "fdo-details",
                        );
                     return response()->json($response);
             }   
        }
        elseif($request->export_type == 'PDF')
        {
                $from_date=$request->from_date;
                $end_date=$request->end_date;
                $status=$request->status;
                $data['fdo_code']=$request->fdo_code;
                $data['name']=$request->name;
                $data['account_no']=$request->account_no;
                $data['bank_name']=$request->bank_name; 
                $data['dob']=$request->dob;
                $data['ifsc_code']=$request->ifsc_code;
                $data['pan_no']=$request->pan_no;
               

                $data['fdos']=$fdos;

                $pdf = FacadePdf::loadView('pages.reports.fdo-details.pdf',compact('data'));
                $pdf->setPaper('A4', 'portrait');
                $path = public_path() . '/storage/reports/fdo_details/';
                if(!is_dir($path)){
                    mkdir($path);
                }
                $fileName =  time().'.'. 'pdf' ;
                $pdf->save($path . '/' . $fileName);

                $pdf_url =  public_path() . '/storage/reports/fdo_details/'.$fileName;
                $headers = array('Content-Type'=> 'application/pdf');
                return response()->download($pdf_url);
        }
        

        
       
    }

     public function export_excel_fdo_data($request)
    {
        $status=($request->status)?($request->status == 'Active'?1:0) : '';
        $start_date=($request->from_date)?$request->from_date : '';
        $end_date=($request->end_date)?$request->end_date : '';
        $code='';
        $name='';
        $account_number='';
        $bank_name='';
        $dob='';
        $ifsc_code='';
        $pan_no='';
        $created_on='';
        if($request->select_column)
        {
            foreach ($request->select_column as $key => $column) {
                if($column == 'code')
                {
                    $code=$column;
                }

                if($column == 'name')
                {
                    $name=$column;
                }
                if($column == 'account_no')
                {
                    $account_number=$column;
                }
                if($column == 'bank_name')
                {
                    $bank_name=$column;
                }
                if($column == 'dob')
                {
                    $dob=$column;
                }
                if($column == 'ifsc_code')
                {
                    $ifsc_code=$column;
                }
                if($column == 'pan_no')
                {
                    $pan_no=$column;
                }
                if($column == 'created_on')
                {
                    $created_on=$column;
                }
                # code...
            }
        }

        $myFile = Excel::raw(new FdoDetailExport($code, $account_number,$bank_name,$name,$ifsc_code, $pan_no,$status,$start_date,$end_date), 'Xlsx');

        return $myFile;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }
}
