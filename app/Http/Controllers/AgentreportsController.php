<?php

namespace App\Http\Controllers;
use App\Models\{Agent, Fdo};
use App\Exports\AgentDetailExport;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
class AgentreportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data['fdos'] = Fdo::where('status',1)->get(['id','code','prefix','first_name','middle_name','last_name']);
        return view('pages.reports.agent-details.index',compact('data'));
    }

    public function export_agent_detail(Request $request)
    {
        $agents=Agent::where('status','!=',2);

        if ($request->fdo != '') {
            $agents->where('fdo_id', $request->fdo);
        }
        if ($request->status != '') {
            $status=($request->status == 'Active')? 1 : 0;
            $agents->where('status', $status);
        }

        if (isset($request->from_date) && isset($request->end_date)) {
            $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
            $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
            $agents->whereBetween('created_at', [$from_date, $to_date]);
        }
        $agents = $agents->latest()->get(['code','prefix','first_name','middle_name','last_name','account_number','bank_name','dob','ifsc_code','pancard_number','created_at']);

        
        if($request->export_type == 'Excel')
        {
            if(!empty($agents) && sizeof($agents) != 0)
            {
                $myFile= $this->export_excel_agent_data($request);
                $response = array(
                        'data'=>$agents,
                        'status' => true,
                        'type'=>'Excel',
                        'name' => "Agent-Details-Report.xlsx",
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
                            'redirect_url' => "agent-details",
                        );
                        return response()->json($response);
                }   
        }
        elseif($request->export_type == 'PDF')
        {            
            $fdo=$request->fdo;
            $from_date=$request->from_date;
            $end_date=$request->end_date;
            $status=$request->status;
            $data['fdo']=$request->fdo;
            $data['agent_code']=$request->agent_code;
            $data['name']=$request->name;
            $data['account_no']=$request->account_no;
            $data['bank_name']=$request->bank_name; 
            $data['dob']=$request->dob;
            $data['ifsc_code']=$request->ifsc_code;
            $data['pan_no']=$request->pan_no;
            $data['created_on']=$request->created_on;
            

            $data['agents']=$agents;

            $pdf = FacadePdf::loadView('pages.reports.agent-details.pdf',compact('data'));
            $pdf->setPaper('A4', 'portrait');
            $path = public_path() . '/storage/reports/agent_details/';
            $fileName =  time().'.'. 'pdf' ;
            if(!is_dir($path)){
                mkdir($path);
            }
            $pdf->save($path . $fileName);
            $pdf_url =  public_path() . '/storage/reports/agent_details/'.$fileName;
            $headers = array('Content-Type'=> 'application/pdf');
            return response()->download($pdf_url);
        } 
    }

    public function export_excel_agent_data($request)
    {
        $fdo_id=($request->fdo)? $request->fdo: '';
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

        $myFile = Excel::raw(new AgentDetailExport($fdo_id,$code,$account_number,$bank_name,$name,$dob,$ifsc_code ,$pan_no ,$created_on,$status,$start_date,$end_date), 'Xlsx');

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
