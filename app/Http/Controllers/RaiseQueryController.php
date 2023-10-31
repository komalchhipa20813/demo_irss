<?php

namespace App\Http\Controllers;

use App\Exports\{ExportRaiseQuery,ExportSolvedQuery};
use App\Models\{RaiseQuery,Agent,Company,MotorPolicy,HealthPolicy,SmePolicy};
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class RaiseQueryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['companies'] = Company::where('status', 1)->get(['id', 'name']);
        return view('pages.raise-query.index', compact('data'));
    }

    public function listing(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
        $totalRecords = RaiseQuery::where('status', 1)->count();
        $raise_queries = RaiseQuery::where('status',1)->with('motor_policy','health_policy','sme_policy');
        if ($request->ajax() && !empty($request->all())) {
            if (isset($request->agent)) {
                $raise_queries->where(function( $subquery ) use ($request){
                    $subquery->whereHas('motor_policy', function ( $query ) use ($request){
                        $query->WhereHas('agent_only', function ($aquery) use ($request) {
                                    $aquery->where('code', $request->agent);
                                });
                    } );
                    $subquery->orWhereHas('health_policy', function ($query) use ($request) {
                            $query->WhereHas('agent_only', function ($hquery) use ($request) {
                                $hquery->where('code', $request->agent);
                            });
                     });
                    $subquery->orWhereHas('sme_policy', function ($query) use ($request) {
                            $query->WhereHas('agent', function ($squery) use ($request) {
                                $squery->where('code', $request->agent);
                            });
                        });
                });
            }

            if (isset($request->product) && $request->product != '0') {
                $raise_queries->where('policy_type',$request->product);
            }
            
            if (isset($request->company)) {
                $raise_queries->where(function( $subquery ) use ($request){
                    $subquery->WhereHas('motor_policy', function ($query) use ($request) {
                        $query->where('company_id', $request->company);
                     });
                    $subquery->orWhereHas('health_policy', function ($query) use ($request) {
                        $query->where('company_id', $request->company);
                    });
                    $subquery->orWhereHas('sme_policy', function ($query) use ($request) {
                        $query->where('company_id', $request->company);
                    });
                });
            }
           
            if (isset($request->inward_no)) {
                $raise_queries->where(function( $subquery ) use ($request){
                    $subquery->WhereHas('motor_policy', function ($query) use ($request) {
                        $query->where('inward_no', $request->inward_no);
                    });
                    $subquery->orWhereHas('health_policy', function ($query) use ($request) {
                        $query->where('inward_no', $request->inward_no);
                    });
                    $subquery->orWhereHas('sme_policy', function ($query) use ($request) {
                        $query->where('inward_no', $request->inward_no);
                    });
                });
            }

            if (isset($request->paased_days)) {

                $passed_days_date = Carbon::now()->subDays($request->paased_days);
                $passed_days_date_from = $passed_days_date->format('Y-m-d 00:00:00');
                $passed_days_date_to = $passed_days_date->format('Y-m-d 23:59:59');

                $raise_queries->whereBetween('raised_on', [$passed_days_date_from,$passed_days_date_to]);
             }
            
             if (isset($request->from_date) && isset($request->end_date)) {
                $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
                $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                $raise_queries->whereBetween('raised_on', [$from_date, $to_date]);
            } else {
                if (isset($request->from_date)) {
                    $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
                    $raise_queries->where('raised_on', $from_date);
                }
                if (isset($request->end_date)) {
                    $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                    $raise_queries->where('raised_on', $to_date);
                }
            }
        }
       
        $totalRecordswithFilter = $raise_queries->count();
        $raise_queries = $raise_queries
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $permissionList = isset(Auth::user()->id) ? permission() : '';
        $records = [];
        if (isset($raise_queries) && !empty($raise_queries)) {
            foreach ($raise_queries as $key => $row) {
                $button = '';
                if (isset(Auth::user()->id) && in_array("111", $permissionList)) {
                    $button .= '<a href="' . route('raise-query.edit', encryptid($row['id'])) . '"><button class=" btn btn-sm btn-success m-1">
                    <i class="mdi mdi-square-edit-outline"></i>
                    </button></a>';
                }

                if (isset(Auth::user()->id) && in_array("111", $permissionList)) {
                    $button .= '<a class="solved-query-status" data-id="'.encryptid($row['id']).'"><button class=" btn btn-sm btn-success m-1">
                    <i class=" mdi  mdi-lightbulb-outline"></i>
                    </button></a>';
                }

                if (isset(Auth::user()->id) && in_array("111", $permissionList)) {
                    $button .= '<button class="delete btn btn-sm btn-danger m-1" data-id="'.encryptid($row['id']).'">
                    <i class="mdi mdi-delete"></i>
                    </button>';
                }

                $agent_code='';
                $inward_no='';
                $sub_product='';
                $registration_no='';
                $company='';
                $product='';

                if(!empty($row->motor_policy))
                {
                    $agent_code=$row->motor_policy->agent_only->code;
                    $inward_no=$row->motor_policy->inward_no;
                    $sub_product=(!empty($row->motor_policy->sub_product_id))?$row->motor_policy->sub_product->name :'';
                    $registration_no=$row->motor_policy->motor_policy_vehicle_only->registration_no;
                    $company=$row->motor_policy->company->name;
                    $product='MOTOR';
                }
                elseif(!empty($row->health_policy))
                {
                    $agent_code=$row->health_policy->agent_only->code;
                    $inward_no=$row->health_policy->inward_no;
                    $sub_product=(!empty($row->health_policy->sub_product_id))?$row->health_policy->sub_product->name : '';
                    $company=$row->health_policy->company->name;
                    $product='HEALTH';
                }
                elseif(!empty($row->sme_policy))
                {
                    $agent_code=$row->sme_policy->agent->code;
                    $inward_no=$row->sme_policy->inward_no;
                    $sub_product= (!empty($row->sme_policy->sub_product_id))?$row->sme_policy->sub_product->name : '';
                    $company=$row->sme_policy->company->name;
                    $product='SME';
                }


                // passed Days
                $currentDateTime = Carbon::now();
                $raise_on_date= Carbon::parse($row->raised_on);
                $pass_days=$currentDateTime->diffInDays($raise_on_date);
                
                $records[] = array(
                    '0' => $row->ticket_no,
                    '1' => $agent_code,
                    '2' => $inward_no,
                    '3' => $product,
                    '4' => $sub_product,
                    '5' => $registration_no,
                    '6' => $company,
                    '7' => $row->details,
                    '8' => Carbon::parse($row->raised_on)->format('d-m-Y'),
                    '9' => $pass_days,
                    '10'=> $row->remark,
                    '11'=>$button
                );
            }
        }
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $records
        );
        return response($response);
    }

    public function getPolicyData(Request $request)
    {
        $raise_query=RaiseQuery::where('status',1)->where('id',decryptid($request->raise_query_id))->first();
        if($request->policy_type == 1)
        {
            $policy_data = MotorPolicy::where('status', '!=', 2)->get(['id','inward_no']);
            $exit_policy_id=(!empty($raise_query)) ? $raise_query->motor_policy_id : '';
        }
        elseif($request->policy_type == 2)
        {
            $policy_data = HealthPolicy::query()->where('status', '!=', 2)->get(['id','inward_no']);
            $exit_policy_id=(!empty($raise_query)) ? $raise_query->health_policy_id : '';;
        }
        elseif($request->policy_type == 3)
        {
            $policy_data = SmePolicy::query()->where('status', '!=', 2)->get(['id','inward_no']);
            $exit_policy_id=(!empty($raise_query)) ? $raise_query->sme_policy_id : '';;
        }
        else
        {
            $policy_data='';
            $exit_policy_id='';
        }

        if(!empty($policy_data))
        {
            $response = [
                'status' => true,
                'exit_policy_id'=>$exit_policy_id,
                'policy' => $policy_data
            ];
        }
        else
        {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
        }

        return response($response);
    }


    public function statusUpdate(Request $request)
    {
        try {
            $raise_query= RaiseQuery::where('id',decryptid($request->raise_query_id))->first();
            $currentDateTime = Carbon::parse($request->close_date);;
            $raise_on_date= Carbon::parse($raise_query->raised_on);
            $tat=$currentDateTime->diffInDays($raise_on_date);

            $update['status'] = 2;
            $update['closed_date']= Carbon::parse($request->close_date)->format('Y-m-d H:i:s');
            $update['closed_by']=Auth::user()->id;
            $update['tat']=$tat;
            $result= RaiseQuery::where('id',decryptid($request->raise_query_id))->update($update);
            if($result)
            {
                $response = [
                    'status' => true,
                    'message' => "Status Change Successfully",
                    'icon' => 'success',
                ];
            }
            else
            {
                $response = [
                    'data'=>decryptid($request->raise_query_id),
                    'status' => false,
                    'message' => "Something Went Wrong! Please Try Again.",
                    'icon' => 'error',
                ];
            }

            
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $raise_query='';
        return view('pages.raise-query.create', compact('raise_query'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate(
                [
                    'policy_type' => 'required',
                    'policy_id' => 'required',
                    'raised_on' => 'required',
                    'details' => 'required',
                ]
            );
            $totalRecords = RaiseQuery::count();
            if(decryptid($request->raise_query_id) == 0)
            {
                $ticket_no = 'TCKT00'.($totalRecords + 1);
            }
            else{
               $raise_query= RaiseQuery::find(decryptid($request->raise_query_id));
               $ticket_no=$raise_query['ticket_no'];
            }
            $result = RaiseQuery::updateOrCreate(
                [
                    'id' => decryptid($request->raise_query_id),
                ],
                [
                    'ticket_no' => $ticket_no,
                    'motor_policy_id'=> ($request->policy_type == 1) ? $request->policy_id : NULL,
                    'health_policy_id'=> ($request->policy_type == 2) ? $request->policy_id : NULL,
                    'sme_policy_id'=> ($request->policy_type == 3) ? $request->policy_id : NULL,
                    'raised_on' => Carbon::parse($request->raised_on)->format('Y-m-d H:i:s'),
                    'policy_type'=> $request->policy_type,
                    'details' =>$request->details,
                    'remark' =>$request->remark
                ]

            );

            if($result)
            {
                $response = [
                    'status' => true,
                    'message' => 'Raise Query ' . $request->code.(decryptid($request->raise_query_id) == '0' ? ' Created' : ' Updated') . ' Successfully',
                    'icon' => 'success',
                    'redirect_url' => "raise-query",
                ];
            }

        }catch (\Throwable $e) {
            dd($e);
            $response = [
                
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
                'redirect_url' => "raise-query",
            ];
        }
        return response($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RaiseQuery  $raiseQuery
     * @return \Illuminate\Http\Response
     */
    public function show(RaiseQuery $raiseQuery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RaiseQuery  $raiseQuery
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $raise_query=RaiseQuery::where('status',1)->where('id',decryptid($id))->first();
            return view('pages.raise-query.create', compact('raise_query'));
        } catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
            return response($response);
        }
    }

    //Export Raise Query

    public function exportRaiseQuery(Request $request)
    {

        $raise_queries = RaiseQuery::where('status',1)->with('motor_policy','health_policy','sme_policy');
        if ($request->ajax() && !empty($request->all())) {
            if (isset($request->agent)) {
                $raise_queries->where(function( $subquery ) use ($request){
                    $subquery->whereHas('motor_policy', function ( $query ) use ($request){
                        $query->WhereHas('agent_only', function ($aquery) use ($request) {
                                    $aquery->where('code', $request->agent);
                                });
                    } );
                    $subquery->orWhereHas('health_policy', function ($query) use ($request) {
                            $query->WhereHas('agent_only', function ($hquery) use ($request) {
                                $hquery->where('code', $request->agent);
                            });
                     });
                    $subquery->orWhereHas('sme_policy', function ($query) use ($request) {
                            $query->WhereHas('agent', function ($squery) use ($request) {
                                $squery->where('code', $request->agent);
                            });
                        });
                });
            }

            if (isset($request->product) && $request->product != '0') {
                $raise_queries->where('policy_type',$request->product);
            }
            
            if (isset($request->company)) {
                $raise_queries->where(function( $subquery ) use ($request){
                    $subquery->WhereHas('motor_policy', function ($query) use ($request) {
                        $query->where('company_id', $request->company);
                     });
                    $subquery->orWhereHas('health_policy', function ($query) use ($request) {
                        $query->where('company_id', $request->company);
                    });
                    $subquery->orWhereHas('sme_policy', function ($query) use ($request) {
                        $query->where('company_id', $request->company);
                    });
                });
            }
           
            if (isset($request->inward_no)) {
                $raise_queries->where(function( $subquery ) use ($request){
                    $subquery->WhereHas('motor_policy', function ($query) use ($request) {
                        $query->where('inward_no', $request->inward_no);
                    });
                    $subquery->orWhereHas('health_policy', function ($query) use ($request) {
                        $query->where('inward_no', $request->inward_no);
                    });
                    $subquery->orWhereHas('sme_policy', function ($query) use ($request) {
                        $query->where('inward_no', $request->inward_no);
                    });
                });
            }

            if (isset($request->paased_days)) {

                $passed_days_date = Carbon::now()->subDays($request->paased_days);
                $passed_days_date_from = $passed_days_date->format('Y-m-d 00:00:00');
                $passed_days_date_to = $passed_days_date->format('Y-m-d 23:59:59');

                $raise_queries->whereBetween('raised_on', [$passed_days_date_from,$passed_days_date_to]);
             }
            
             if (isset($request->from_date) && isset($request->end_date)) {
                $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
                $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                $raise_queries->whereBetween('raised_on', [$from_date, $to_date]);
            } else {
                if (isset($request->from_date)) {
                    $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
                    $raise_queries->where('raised_on', $from_date);
                }
                if (isset($request->end_date)) {
                    $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                    $raise_queries->where('raised_on', $to_date);
                }
            }
        }

        $totalRecordswithFilter = $raise_queries->count();
        $raise_queries = $raise_queries->get();

        if (isset($raise_queries) && !empty($raise_queries)) {
            $myFile = Excel::raw(new ExportRaiseQuery($request->data), 'Xlsx');
            $response = array(
                'status' => 'true',
                'name' => "RaiseQuery_data.xlsx",
                'file' => "data:application/vnd.ms-excel;base64," . base64_encode($myFile),
            );
        }
        else {
            $response = array(
                'data' => $motorPolicy,
                'status' => false,
                'icon' => 'error',
                'msg' => "No Data available",
            );
        }

        return  $response;


    }

    public function solvedIndex()
    {
        $data['companies'] = Company::where('status', 1)->get(['id', 'name']);
        return view('pages.raise-query.solvedIndex', compact('data'));
    }


    //Solved Query listing
    public function listingOfSolvedQuery(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
        $totalRecords = RaiseQuery::where('status', 2)->count();
        $raise_queries = RaiseQuery::where('status',2)->with('motor_policy','health_policy','sme_policy','query_closed_by');
        $a='';
        if ($request->ajax() && !empty($request->all())) {
            if (isset($request->agent)) {
                $raise_queries->where(function( $subquery ) use ($request){
                    $subquery->whereHas('motor_policy', function ( $query ) use ($request){
                        $query->WhereHas('agent_only', function ($aquery) use ($request) {
                                    $aquery->where('code', $request->agent);
                                });
                    } );
                    $subquery->orWhereHas('health_policy', function ($query) use ($request) {
                            $query->WhereHas('agent_only', function ($hquery) use ($request) {
                                $hquery->where('code', $request->agent);
                            });
                     });
                    $subquery->orWhereHas('sme_policy', function ($query) use ($request) {
                            $query->WhereHas('agent', function ($squery) use ($request) {
                                $squery->where('code', $request->agent);
                            });
                        });
                });
            }

            if (isset($request->product) && $request->product != '0') {
                $raise_queries->where('policy_type',$request->product);
            }
            
         
            if (isset($request->company)) {
                $raise_queries->where(function( $subquery ) use ($request){
                    $subquery->WhereHas('motor_policy', function ($query) use ($request) {
                        $query->where('company_id', $request->company);
                     });
                    $subquery->orWhereHas('health_policy', function ($query) use ($request) {
                        $query->where('company_id', $request->company);
                    });
                    $subquery->orWhereHas('sme_policy', function ($query) use ($request) {
                        $query->where('company_id', $request->company);
                    });
                });
            }
           
            if (isset($request->inward_no)) {
                $raise_queries->where(function( $subquery ) use ($request){
                    $subquery->WhereHas('motor_policy', function ($query) use ($request) {
                        $query->where('inward_no', $request->inward_no);
                    });
                    $subquery->orWhereHas('health_policy', function ($query) use ($request) {
                        $query->where('inward_no', $request->inward_no);
                    });
                    $subquery->orWhereHas('sme_policy', function ($query) use ($request) {
                        $query->where('inward_no', $request->inward_no);
                    });
                });
            }

            if (isset($request->paased_days)) {
                $raise_queries->where('tat', $request->paased_days);
             }
            
             if (isset($request->from_date) && isset($request->end_date)) {
                $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
                $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                $raise_queries->whereBetween('raised_on', [$from_date, $to_date]);
            } else {
                if (isset($request->from_date)) {
                    $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
                    $raise_queries->where('raised_on', $from_date);
                }
                if (isset($request->end_date)) {
                    $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                    $raise_queries->where('raised_on', $to_date);
                }
            }
           
        }
       
        $totalRecordswithFilter = $raise_queries->count();
        $raise_queries = $raise_queries
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $permissionList = isset(Auth::user()->id) ? permission() : '';
        $records = [];
        if (isset($raise_queries) && !empty($raise_queries)) {
            foreach ($raise_queries as $key => $row) {

                $agent_code='';
                $inward_no='';
                $sub_product='';
                $registration_no='';
                $company='';
                $product='';

                if(!empty($row->motor_policy))
                {
                    $agent_code=$row->motor_policy->agent_only->code;
                    $inward_no=$row->motor_policy->inward_no;
                    $sub_product=(!empty($row->motor_policy->sub_product_id))?$row->motor_policy->sub_product->name :'';
                    $registration_no=$row->motor_policy->motor_policy_vehicle_only->registration_no;
                    $company=$row->motor_policy->company->name;
                    $product='MOTOR';
                }
                elseif(!empty($row->health_policy))
                {
                    $agent_code=$row->health_policy->agent_only->code;
                    $inward_no=$row->health_policy->inward_no;
                    $sub_product=(!empty($row->health_policy->sub_product_id))?$row->health_policy->sub_product->name : '';
                    $company=$row->health_policy->company->name;
                    $product='HEALTH';
                }
                elseif(!empty($row->sme_policy))
                {
                    $agent_code=$row->sme_policy->agent->code;
                    $inward_no=$row->sme_policy->inward_no;
                    $sub_product= (!empty($row->sme_policy->sub_product_id))?$row->sme_policy->sub_product->name : '';
                    $company=$row->sme_policy->company->name;
                    $product='SME';
                }


               

                $records[] = array(
                    '0' => $row->ticket_no,
                    '1' => $agent_code,
                    '2' => $inward_no,
                    '3' => $product,
                    '4' => $sub_product,
                    '5' => $registration_no,
                    '6' => $company,
                    '7' => $row->details,
                    '8' => Carbon::parse($row->raised_on)->format('d-m-Y'),
                    '9' => $row->tat,
                    '10'=> $row->remark,
                    '11'=>(!empty($row->closed_by)) ? ucwords((!empty($row->query_closed_by->prefix)) ?$row->query_closed_by->prefix.'.':'' .$row->query_closed_by->first_name .' '.$row->query_closed_by->middle_name .' '. $row->query_closed_by->last_name) : '',
                    '12'=> Carbon::parse($row->closed_date)->format('d-m-Y')
                );
            }
        }
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $records
        );
        return response($response);
    }

    //Export Solved Query

    public function exportSolvedQuery(Request $request)
    {

        $raise_queries = RaiseQuery::where('status',2)->with('motor_policy','health_policy','sme_policy','query_closed_by');
        if ($request->ajax() && !empty($request->all())) {
            if (isset($request->agent)) {
                $raise_queries->where(function( $subquery ) use ($request){
                    $subquery->whereHas('motor_policy', function ( $query ) use ($request){
                        $query->WhereHas('agent_only', function ($aquery) use ($request) {
                                    $aquery->where('code', $request->agent);
                                });
                    } );
                    $subquery->orWhereHas('health_policy', function ($query) use ($request) {
                            $query->WhereHas('agent_only', function ($hquery) use ($request) {
                                $hquery->where('code', $request->agent);
                            });
                     });
                    $subquery->orWhereHas('sme_policy', function ($query) use ($request) {
                            $query->WhereHas('agent', function ($squery) use ($request) {
                                $squery->where('code', $request->agent);
                            });
                        });
                });
            }

            if (isset($request->product) && $request->product != '0') {
                $raise_queries->where('policy_type',$request->product);
            }
            
            if (isset($request->company)) {
                $raise_queries->where(function( $subquery ) use ($request){
                    $subquery->WhereHas('motor_policy', function ($query) use ($request) {
                        $query->where('company_id', $request->company);
                     });
                    $subquery->orWhereHas('health_policy', function ($query) use ($request) {
                        $query->where('company_id', $request->company);
                    });
                    $subquery->orWhereHas('sme_policy', function ($query) use ($request) {
                        $query->where('company_id', $request->company);
                    });
                });
            }
           
            if (isset($request->inward_no)) {
                $raise_queries->where(function( $subquery ) use ($request){
                    $subquery->WhereHas('motor_policy', function ($query) use ($request) {
                        $query->where('inward_no', $request->inward_no);
                    });
                    $subquery->orWhereHas('health_policy', function ($query) use ($request) {
                        $query->where('inward_no', $request->inward_no);
                    });
                    $subquery->orWhereHas('sme_policy', function ($query) use ($request) {
                        $query->where('inward_no', $request->inward_no);
                    });
                });
            }

            if (isset($request->paased_days)) {
                $raise_queries->where('tat', $request->paased_day);
             }
            
             if (isset($request->from_date) && isset($request->end_date)) {
                $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
                $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                $raise_queries->whereBetween('raised_on', [$from_date, $to_date]);
            } else {
                if (isset($request->from_date)) {
                    $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
                    $raise_queries->where('raised_on', $from_date);
                }
                if (isset($request->end_date)) {
                    $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                    $raise_queries->where('raised_on', $to_date);
                }
            }
        }

        $totalRecordswithFilter = $raise_queries->count();
        $raise_queries = $raise_queries->get();

        if (isset($raise_queries) && !empty($raise_queries)) {
            $myFile = Excel::raw(new ExportSolvedQuery($request->data), 'Xlsx');
            $response = array(
                'status' => 'true',
                'name' => "solvedQuery_data.xlsx",
                'file' => "data:application/vnd.ms-excel;base64," . base64_encode($myFile),
            );
        }
        else {
            $response = array(
                'data' => $motorPolicy,
                'status' => false,
                'icon' => 'error',
                'msg' => "No Data available",
            );
        }

        return  $response;


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RaiseQuery  $raiseQuery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RaiseQuery $raiseQuery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RaiseQuery  $raiseQuery
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $update['status'] = 4;
            RaiseQuery::where('id',decryptid($id))->update($update);
            $response = [
                'status' => true,
                'message' => "Raise Query Deleted Successfully",
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
}
