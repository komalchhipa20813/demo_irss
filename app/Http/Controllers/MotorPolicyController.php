<?php

namespace App\Http\Controllers;

use App\Exports\{ExportMotorPolicy, ExportUpdateMotorPolicy};
use App\Models\{Agent, Bank, Company, Customer, IrssBranch, Make, MotorPolicy, Product, MotorPolicyPayment, MotorPolicyVehical, BranchImdName, HealthPolicy, Settings, SmePolicy};
use App\Notifications\PolicyAddNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Response;

class MotorPolicyController extends Controller
{
    // dashboard of motor policy
    public function index()
    {

        $data['irss_branches'] = IrssBranch::where('status', 1)->get(['id', 'name']);
        $data['companies'] = Company::where('status', 1)->get(['id', 'name']);
        $data['products'] = Product::where('status', 1)->where('policy_type', 1)->get(['id', 'name']);
        $data['agents'] = Agent::where('status', 1)->get(['id', 'first_name', 'last_name', 'middle_name', 'prefix', 'code']);
        if (isset(Auth::user()->id))
            return view('pages.motor-policy.index', compact('data'));
        else
            return view('pages.motor-policy.indexAgent', compact('data'));
    }

    /* listing of motor policy */
    public function listing(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
        $totalRecords = MotorPolicy::where('status', '!=', 2)->count();
        $motorPolicy = MotorPolicy::where('status', '!=', 2)->with('customer', 'company', 'agent_only', 'motor_policy_vehicle_only');
        if ($request->ajax() && !empty($request->all())) {
            if (isset($request->agent)) {
                $motorPolicy->where('agent_id', $request->agent);
            }
            if (isset($request->name)) {
                $motorPolicy->whereHas('customer', function ($query) use ($request) {
                    $query->where('first_name', 'like', '%' . $request->name . '%')
                        ->orWhere('middle_name', 'like', '%' . $request->name . '%')
                        ->orWhere('last_name', 'like', '%' . $request->name . '%');
                });
            }
            if (isset($request->branch)) {
                $motorPolicy->where('irss_branch_id', $request->branch);
            }
            if (isset($request->company)) {
                $motorPolicy->where('company_id', $request->company);
            }
            if (isset($request->cheque_no)) {
                $motorPolicy->whereHas('payments', function ($query) use ($request) {
                    $query->where('payment_type', 2)->where('number', $request->cheque_no);
                });
            }
            if (isset($request->inward_no)) {
                $motorPolicy->where('inward_no', 'like', '%' . $request->inward_no . '%');
            }
            if (isset($request->policy_no)) {
                $motorPolicy->where('policy_number', $request->policy_no);
            }
            if (isset($request->product)) {
                $motorPolicy->whereHas('product', function ($query) use ($request) {
                    $query->where('id', $request->product);
                });
            }
            if (isset($request->policy_no)) {
                $motorPolicy->where('policy_number', $request->policy_no);
            }
            if (isset($request->engine_no)) {
                $motorPolicy->whereHas('motor_policy_vehicle', function ($query) use ($request) {
                    $query->where('engine_no', $request->engine_no);
                });
            }
            if (isset($request->chasis_no)) {
                $motorPolicy->whereHas('motor_policy_vehicle', function ($query) use ($request) {
                    $query->where('chasiss_no', $request->chasis_no);
                });
            }
            if (isset($request->registration_no)) {
                // dd(realVehicleNo($request->registration_no));
                $motorPolicy->whereHas('motor_policy_vehicle', function ($query) use ($request) {
                    $query->where('registration_no', realVehicleNo($request->registration_no));
                });
            }
            if (isset($request->policy_start_date) && isset($request->policy_end_date)) {
                $motorPolicy->where('start_date', '>=', $request->policy_start_date)->where('end_date', '<=', $request->policy_end_date);
            } else {
                if (isset($request->policy_start_date)) {
                    $motorPolicy->where('start_date', $request->policy_start_date);
                }
                if (isset($request->policy_end_date)) {
                    $motorPolicy->where('end_date', $request->policy_end_date);
                }
            }
            if (isset($request->from_date) && isset($request->end_date)) {
                $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
                $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                $motorPolicy->whereBetween('created_at', [$from_date, $to_date]);
            } else {
                if (isset($request->from_date)) {
                    $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
                    $motorPolicy->where('created_at', $from_date);
                }
                if (isset($request->end_date)) {
                    $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                    $motorPolicy->where('created_at', $to_date);
                }
            }
        }
        if (!isset(Auth::user()->id)) {
            isset(Auth::guard('agent')->user()->id) ?
                $motorPolicy->where('agent_id', Auth::guard('agent')->user()->id) :
                $motorPolicy->whereHas('agent', function ($query) use ($request) {
                    $query->where('fdo_id', Auth::guard('fdo')->user()->id);
                });
        }
        $totalRecordswithFilter = $motorPolicy->count();
        $motorPolicy = $motorPolicy
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $permissionList = isset(Auth::user()->id) ? permission() : '';
        $records = [];
        if (isset($motorPolicy) && !empty($motorPolicy)) {
            foreach ($motorPolicy as $key => $row) {
                $button = '';

                if (isset(Auth::user()->id) && in_array("109", $permissionList)) {
                    $button .= '<a href="' . route('motor-policy.show', encryptid($row['id'])) . '"><button class="btn btn-sm btn-success m-1"  data-id="' . encryptid($row['id']) . '" >
                    <i class="mdi mdi-view-module"></i>
                    </button></a>';
                }
                if (isset(Auth::user()->id) && in_array("111", $permissionList)) {
                    $button .= '<a href="' . route('motor-policy.edit', encryptid($row['id'])) . '"><button class=" btn btn-sm btn-success m-1">
                    <i class="mdi mdi-square-edit-outline"></i>
                    </button></a>';
                }
                if (isset(Auth::user()->id) && in_array("112", $permissionList)) {
                    $button .= '<button class="delete btn btn-sm btn-danger m-1" data-id="' . encryptid($row['id']) . '">
                    <i class="mdi mdi-delete"></i>
                    </button>';
                    if ($row->status != 3) {
                        $button .= '<button class="btn btn-sm btn-danger m-1 cancel" data-id="' . encryptid($row['id']) . '">
                        <i class="mdi mdi-close"></i>
                        </button>';
                    }
                }
                $policy_number = $row->policy_copy_status == 2 ? '<a href="' . route('policy.download', encryptid($row->policy_copy)) . '" class="policy_copy_download">' . $row->policy_number . '.pdf</a>' : (!empty($row->policy_number) ? $row->policy_number . '<form id="policy_copy_form"><input type="hidden" name="id" class="id" id="id" value="' . encryptid($row['id']) . '"><input class="form-control" name="policy_copy" type="file" id="policy_copy" accept="application/pdf"><button class="btn btn-primary submit_policy_copy" type="button">Save</button></form>' : '');
                $records[] = array(
                    '0' => $policy_number,
                    '1' => !empty($row->motor_policy_vehicle_only) ? vehicleNO($row->motor_policy_vehicle_only->registration_no) : '',
                    '2' => 'MOTOR',
                    '3' => !empty($row->company) ? getFirstString($row->company->name) : '',
                    '4' => !empty($row->agent_only) ? $row->agent_only->code : '',
                    '5' => $row->customer->first_name . ' ' . $row->customer->middle_name . ' ' . $row->customer->last_name,
                    '6' => $row->inward_no,
                    '7' => $button,
                    '8' => $row->status
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

    // redirecting to create page
    public function create()
    {
        try {
            $data['products'] = Product::where('status', 1)->where('policy_type', 1)->get(['id', 'name']);
            $data['customers'] = Customer::where('status', 1)->get(['id', 'first_name', 'last_name', 'middle_name', 'prefix', 'customer_code']);
            $data['agents'] = Agent::where('status', 1)->get(['id', 'first_name', 'last_name', 'middle_name', 'prefix', 'code']);
            $data['irss_branches'] = IrssBranch::where('status', 1)->get(['id', 'name']);
            $data['banks'] = Bank::where('status', 1)->get(['id', 'name']);
            $data['policy'] = null;


            return view('pages.motor-policy.create', compact('data'));
        } catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
            return response($response);
        }
    }

    /* Check The Policy In The Data */
    public function policy_check(Request $request)
    {
        if (isset($request) && $request->id && $request->policy_no) {
            $policy = MotorPolicyVehical::where('registration_no', $request->policy_no)->where('status', 1)->where('policy_id', '!=', decryptid($request->id))->first(['id']);
            return !is_null($policy) ? true : false;
        } else {
            return false;
        }
    }

    /* Check The Policy Number In Database */
    public function policy_number_check(Request $request)
    {
        if (isset($request) && $request->id && $request->policy_number) {
            $policy = MotorPolicy::where('policy_number', $request->policy_number)->where('status', 1)->where('id', '!=', decryptid($request->id))->first(['id']);
            return !is_null($policy) ? true : false;
        } else {
            return false;
        }
    }

    /* storing data to database */
    public function store(Request $request)
    {
        // return response($request->all());
        $request->validate(
            [
                'product' => 'required',
                'branch' => 'required',
                'business_date' => 'required',
                'customer' => 'required',
                'agent' => 'required',
                'company' => 'required',
                'registration' => 'required',
                'engine_no' => 'required',
                'chasiss_no' => 'required',
                'make_id' => 'required',
                'model_id' => 'required',
                'variant_id' => 'required',
                'cc_gvw_no' => 'required',
                'manufacturing_year' => 'required',
                'seating_capacity' => 'required',
                'fuel_type' => 'required',
                'gst' => 'required',
                'payment_type' => 'required',
                "policy_copy" => "sometimes|required|mimes:pdf|max:10000"
            ]
        );
        try {
            $policy_image = MotorPolicy::find(decryptid($request->policy_id));
            $url = !empty($policy_image->policy_copy) ? $policy_image->policy_copy : null;
            $policy_copy_status = !empty($policy_image->policy_copy_status) ? $policy_image->policy_copy_status : 1;
            if ($request->has('policy_number') && $request->has('policy_copy')) {
                $company = Company::find($request->company);
                $url = 'policies/motor/' . str_replace(" ", "_", $company->name) . '_' . $company->id . '/' . str_replace('/', '', $request->policy_number) . '.pdf';
                if (Storage::disk('s3')->exists($url))
                    Storage::disk('s3')->delete($url);
                Storage::disk('s3')->put($url, file_get_contents($request->policy_copy));
                $policy_copy_status = 2;
            }
            $motor_policy = MotorPolicy::updateOrCreate([
                'id' => decryptid($request->policy_id),
            ], [
                "policy_type" => $request->policy_type,
                "has_previous_policy" => isset($request->has_policy) ? ($request->has_policy == 'on' ? 2 : 1) : 1,
                'product_id' => $request->product,
                'product_type_id' => $request->product_type,
                'irss_branch_id' => $request->branch,
                'business_date' => $request->business_date,
                'code_type' => (int)$request->code_type,
                'edited_by_id'=>(decryptid($request->policy_id) != 0) ? Auth::user()->id : null,
                'edited_at'=>(decryptid($request->policy_id) != 0) ?  Carbon::now()->format('Y-m-d H:i:s'):null, 
                'customer_id' => $request->customer,
                'agent_id' => $request->agent,
                'company_id' => $request->company,
                'company_branch_id' => $request->company_branch_name,
                'branch_imd_id' => $request->branch_imd,
                'sub_product_id' => $request->sub_product,
                'policy_copy' => $url,
                'policy_tenure' => $request->policy_tenure,
                'policy_number' => $request->policy_number,
                'issue_date' => $request->issue_date,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                "previous_policy_number" => isset($request->has_policy) ? ($request->has_policy == 'on' ? $request->pre_policy_number : null) : null,
                "previous_start_date" => isset($request->has_policy) ? ($request->has_policy == 'on' ? $request->pre_start_date : null) : null,
                "previous_end_date" => isset($request->has_policy) ? ($request->has_policy == 'on' ? $request->pre_end_date : null) : null,
                "previous_company_id" => isset($request->has_policy) ? ($request->has_policy == 'on' ? $request->pre_company : null) : null,
                'renewal_previous_policy_number' => ($request->policy_type == 'renewal') ? (isset($request->renew_pre_policy_number) ? $request->renew_pre_policy_number : null) : null,
                "renewal_previous_company_id" => ($request->policy_type == 'renewal') ? (isset($request->previouspolicycompany) ? $request->previouspolicycompany : null) : null,
                'total_premium' => $request->total_premium,
                'total_idv' => $request->total_idv,
                'discount' => $request->discount,
                'ncb' => $request->ncb,
                "is_od_only" => isset($request->only_od) ? 2 : 1,
                'od' => $request->od,
                'pay_to_owner' => $request->pay_to_owner,
                'addonpremium' => $request->addonpremium,
                'tp' => $request->tp,
                "is_gst_value" => isset($request->gst_value) ? 2 : 1,
                'gst' => $request->gst,
                'stamp_duty' => $request->stamp_duty,
                'nominee_name' => $request->nominee_name,
                'nominee_relation' => $request->nominee_relation,
                'remark' => $request->remark,
                'policy_copy_status' => $policy_copy_status,
            ]);



            // inward number
            /* insert inward or delete payments */
            if (decryptid($request->policy_id) != 0) {
                MotorPolicyPayment::where('policy_id', decryptid($request->policy_id))->delete();
            } else {
                $settings = Settings::where('key', 'policy_no');
                $irss_branch = IrssBranch::where('id', $request->branch)->with('city')->first(['id', 'city_id', 'policy_inward_code']);
                $inward_number = inwardFirstChar($request->policy_type) . str_pad($request->branch, 2, '0', STR_PAD_LEFT) . str_replace('-', '', $irss_branch->city->rto_code) . Carbon::now()->format('y') . $irss_branch->policy_inward_code . $settings->first()->value;
                $settings->update(['value' => str_pad(++$settings->first()->value, 6, '0', STR_PAD_LEFT)]);
                $motor_policy->update(['inward_no' => $inward_number,'created_by_id' => Auth::user()->id]);
            }
            //setup payment data by payment type
            $paymentData = get_payment_field($motor_policy->id, $request);
            MotorPolicyPayment::insert($paymentData);
            decryptid($request->policy_id) != 0 ? MotorPolicyVehical::where('policy_id', decryptid($request->policy_id))->delete() : '';

            MotorPolicyVehical::insert([
                'policy_id' => $motor_policy->id,
                'city_id' => $request->rto_code_id,
                'new_registration_no' => (!empty($request->new_registration)) ? 1 : 0,
                'registration_no' => $request->registration,
                'tp_start_date' => (!empty($request->tp_start_date)) ? $request->tp_start_date : null,
                'tp_end_date' => (!empty($request->tp_end_date)) ? $request->tp_end_date : null,
                'engine_no' => $request->engine_no,
                'chasiss_no' => $request->chasiss_no,
                'make_id' => $request->make_id,
                'model_id' => $request->model_id,
                'variant_id' => $request->variant_id,
                'cc_gvw_no' => $request->cc_gvw_no,
                'manufacturing_year' => $request->manufacturing_year,
                'seating_capacity' => $request->seating_capacity,
                'fuel_type' => $request->fuel_type,
            ]);

            $response = [
                'status' => true,
                'message' => 'Motor Policy ' . $motor_policy->inward_no . (decryptid($request->policy_id) == 0 ? ' Created' : ' Updated') . ' Successfully.',
                'icon' => 'success',
                'redirect_url' => "motor-policy/create",
            ];
            $motor_policy = $motor_policy->toArray();
            $motor_policy['policy_type'] = 'MOTOR';
            $motor_policy['notification_type'] = 'book';
            $motor_policy['message'] = (decryptid($request->policy_id) == 0 ? 'New ' : 'Updated ') . 'Motor Policy(' . $motor_policy['inward_no'] . ') Recieved!';
            Agent::find($request->agent)->notify(new PolicyAddNotification($motor_policy));
        } catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Somethings Went Wrong! Please Try Again.',
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
        try {
            $id = decryptid($id);
            $getPolicy = MotorPolicy::with(['payments', 'customer', 'companyBranch', 'branch_imd_name', 'branch', 'product', 'product_type', 'sub_product', 'motor_policy_vehicle', 'agent', 'created_by', 'updated_by','edited_by', 'previous_company', 'renewal_previous_company','motor_policy_vehicle'])->where('id', $id)->first();

            $company = $getPolicy->companyBranch()->with('company')->first();
            $make = $getPolicy->motor_policy_vehicle()->with('make', 'product_model', 'product_variant', 'bank')->first();
            $payment = $getPolicy->payments()->with('bank')->first();

            $od=(!empty($getPolicy->od))?$getPolicy->od :0;
            $add_on=(!empty($getPolicy->addonpremium))?$getPolicy->addonpremium :0;
            $total_od=round($od + $add_on);
            $tp=(!empty($getPolicy->tp))?$getPolicy->tp :0;
            $pay_to_owner=(!empty($getPolicy->pay_to_owner))?$getPolicy->pay_to_owner :0;
            $total_tp_or_ter=round($tp+$pay_to_owner);
            $net_premium=round($total_od + $total_tp_or_ter);
            $stamp_duty_value=(!empty($getPolicy->stamp_duty))?$getPolicy->stamp_duty :0;
            $total_premium=(!empty($getPolicy->total_premium))?$getPolicy->total_premium :0;
            $gst_value=round(($total_premium-$stamp_duty_value) - $net_premium);
            $data=[
                'total_od'=>$total_od,
                'total_tp_or_ter'=>$total_tp_or_ter,
                'net_premium'=>$net_premium,
                'total_premium'=>$total_premium,
                'gst_value'=>$gst_value,
            ];


            if (!empty($getPolicy)) {
                return view('pages.motor-policy.show', compact(['getPolicy', 'company', 'make', 'payment','data']));
            }
        } catch (\Throwable $e) {
            return redirect('/');
        }
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
            $data['products'] = Product::where('status', 1)->where('policy_type', 1)->get(['id', 'name']);
            $data['customers'] = Customer::where('status', 1)->get(['id', 'first_name', 'last_name', 'middle_name', 'prefix', 'customer_code']);
            $data['agents'] = Agent::where('status', 1)->get(['id', 'first_name', 'last_name', 'middle_name', 'prefix', 'code']);
            $data['irss_branches'] = IrssBranch::where('status', 1)->get(['id', 'name']);
            $data['banks'] = Bank::where('status', 1)->get(['id', 'name']);
            $data['policy'] = MotorPolicy::where('id', decryptid($id))->with('motor_policy_vehicle_only')->first();
            $data['payment_type'] = MotorPolicyPayment::where('policy_id', decryptid($id))->first(['id', 'payment_type']);
            return view('pages.motor-policy.create', compact('data'));
        } catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
            return response($response);
        }
    }
    /*Display Payment Data of Motor Policy*/
    public function get_payment_data(Request $request)
    {
        try {
            $data['payments'] = MotorPolicyPayment::where('policy_id', decryptid($request->policy_id))->get()->toArray();

            if (!is_null($data)) {
                $response = [
                    'data' => $data,
                    'status' => true,
                ];
            }
        } catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => "Something Went Wrong! Please Try Again.",
                'icon' => 'error',
            ];
        }
        return response($response);
    }

    public function destroy($id)
    {
        try {
            $update = ['status' => 2];
            $motor_policy = MotorPolicy::where('id', decryptid($id));
            $motor_policy->update($update);
            $motor_policy = $motor_policy->first();
            MotorPolicyVehical::where('policy_id', decryptid($id))->update($update);
            MotorPolicyPayment::where('policy_id', decryptid($id))->update($update);
            $motor_policy->policy_type = 'MOTOR';
            $motor_policy->notification_type = 'book';
            $motor_policy->message = 'Motor Policy(' . $motor_policy->inward_no . ') Deleted!';
            Agent::find($motor_policy->agent_id)->notify(new PolicyAddNotification($motor_policy));
            $response = [
                'status' => true,
                'message' => "Motor Policy " . $motor_policy->inward_no . " Data Deleted Successfully",
                'icon' => 'success',
            ];
        } catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => "Something Went Wrong! Please Try Again.",
                'icon' => 'error',
            ];
        }
        return response($response);
    }
    public function get_show_data($id)
    {
        try {
            $data = MotorPolicy::find(decryptid($id));
            $response = [
                'data' => $data,
                'status' => true,
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

    public function policy_update()
    {
        $data['irss_branches'] = IrssBranch::where('status', 1)->get(['id', 'name']);
        $data['companies'] = Company::where('status', 1)->get(['id', 'name']);
        $data['products'] = Product::where('status', 1)->where('policy_type', 1)->get(['id', 'name']);
        $data['agents'] = Agent::where('status', 1)->get(['id', 'first_name', 'last_name', 'middle_name', 'prefix', 'code']);

        return view('pages.update-all-policy.update_motor_policy', compact('data'));
    }

    public function update_policy_listing(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
        $totalRecords = MotorPolicy::where('status', '!=', 2)->where('policy_number', null)->count();
        $motorPolicy = motopolicy_data($request);
        $totalRecordswithFilter = $motorPolicy->count();
        $motorPolicy = $motorPolicy
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $records = [];
        $tenures = tenures();
        if (isset($motorPolicy) && !empty($motorPolicy)) {
            $sr = 1;
            foreach ($motorPolicy as $key => $row) {
                // branch HTMl
                $html = '';
                $data_imd = BranchImdName::where('status', 1)->get(["name", "id"]);

                if (!empty($data_imd)) {
                    $html = '<option selected disabled class="input-cstm">Select Branch</option>';
                    foreach ($data_imd as $key => $imd) {
                        $selected = ($imd->id == $row->branch_imd_id) ? 'selected' : '';
                        $html .= '<option ' . $selected . ' value="' . $imd->id . '">' . $imd->name . '</option>';
                    }
                } else {
                    $html = '<option selected disabled class="input-cstm">Please First Enter Branch</option>';
                }



                //End branch
                //
                $policy_tenure_html = '<option selected disabled class="input-cstm">Select</option>';
                foreach ($tenures as $key => $tenure) {
                    if ($tenure == 'ABOVE15YRS') {
                        $tenure_name = 'Above 15 Years';
                    } elseif ($tenure == 'SHORT') {
                        $tenure_name = 'Short Period';
                    } else {
                        $tenure_name = $tenure . ' Year';
                    }
                    $selected = ($tenure == $row->policy_tenure) ? 'selected' : '';
                    $policy_tenure_html .= '<option ' . $selected . ' value="' . $tenure . '">' . $tenure_name . '</option>';
                }

                $records[] = array(
                    '0' => '<input type="checkbox" class="checkbox"  name="checkid[]" id="' . encryptid($row->id) . '"  onclick="single_unselected(this);" data-series="' . $sr . '"   style="    margin-left: 8px;"/><input type="hidden" name="id_' . $sr . '" value="' . encryptid($row->id) . '"><input type="hidden" name="policy_name_' . $sr . '" value="motor">',
                    '1' => '<select class="form-select form-control branch_imd "  name="branch_imd_' . $sr . '" >' . $html . ' </select>',
                    '2' => '- ' . $row->inward_no . '<br>- ' .((!empty($row->company_id))? $row->company->name : '') . '<br>- ' .((!empty($row->agent_id)) ? $row->agent->code : ''),
                    '3' => '- MOTOR <br>- ' . ((!empty($row->product_id)) ? $row->product->name : '') . '<br>- ' . ((!empty($row->sub_product_id)) ? $row->sub_product->name : '') . '<br><br><br><b>Vehicle Details</b><br>- ' .((!empty($row->motor_policy_vehicle)) ?  vehicleNo($row->motor_policy_vehicle->registration_no)  : ''). '<br>- ' . ((!empty($row->motor_policy_vehicle)) ? $row->motor_policy_vehicle->engine_no : '' ) . '<br>- ' . ((!empty($row->motor_policy_vehicle)) ? $row->motor_policy_vehicle->chasiss_no : '' ) . '<br>',
                    '4' => '<p>' . $row->od . '</p><p>' . $row->tp . '</p>',
                    '5' => $row->total_premium,
                    '6' => '<div class="input-group ">
                    <input type="text" name="issue_date_' . $sr . '" value="' . $row->issue_date . '" class="form-control datepicker issue_date" autocomplete="off" id="issue_date">
                    
                    </div>',
                    '7' => '<select class="form-select form-control policy_tenure " id="policy_tenure_' . $sr . '" name="policy_tenure_' . $sr . '" onchange="addYears(' . $sr . ')">' . $policy_tenure_html . ' </select>',
                    '8' => '<div class="input-group ">
                    <input type="text" name="start_date_' . $sr . '" value="' . $row->start_date . '" class="form-control datepicker start_date" autocomplete="off" id="start_date_' . $sr . '" onchange="addYears(' . $sr . ')">
                    
                    </div>',
                    '9' => '<div class="input-group ">
                    <input type="text" name="end_date_' . $sr . '" value="' . $row->end_date . '" class="form-control" autocomplete="off" id="end_date_' . $sr . '" readonly>
                    
                    </div>',
                    '10' => '<div class="input-group ">
                    <input type="text" name="tp_start_date_' . $sr . '" value="' . ((!empty($row->motor_policy_vehicle)) ? $row->motor_policy_vehicle->tp_start_date : '' ) . '" class="form-control  tp_start_date_" autocomplete="off" id="tp_start_date_' . $sr . '"  readonly>
                    
                    </div>',
                    '11' => '<div class="input-group ">
                    <input type="text" name="tp_end_date_' . $sr . '" value="' . ((!empty($row->motor_policy_vehicle)) ? $row->motor_policy_vehicle->tp_end_date : '' ). '" class="form-control" autocomplete="off" id="tp_end_date_' . $sr . '" readonly>
                    
                    </div>',
                    '12' => '<input type="text" class="form-control" name="policy_number_' . $sr . '" id="policy_number"  autocomplete="off"  value="' . $row->policy_number . '">',
                    '13' => '<input class="form-control" name="policy_copy_' . $sr . '" type="file" id="policy_copy" accept="application/pdf">',
                    '14' => (!empty($row->customer)) ? ($row->customer->prefix . ' ' . $row->customer->first_name . ' ' . $row->customer->last_name ) : '',
                );



                $sr++;
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

    public function export(Request $request)
    {
        $motorPolicy = MotorPolicy::where('status', '=', 1)->with('product', 'sub_product', 'customer', 'branch', 'company', 'companyBranch', 'branch_imd_name', 'motor_policy_vehicle');
        if ($request->ajax() && !empty($request->all())) {
            if (isset($request->data['agent']) && !empty($request->data['agent'])) {
                $motorPolicy->where('agent_id', $request->data['agent']);
            }
            if (isset($request->data['name']) && !empty($request->data['name'])) {
                $motorPolicy->whereHas('customer', function ($query) use ($request) {
                    $query->where('first_name', 'like', '%' . $request->data['name'] . '%')
                        ->orWhere('middle_name', 'like', '%' . $request->data['name'] . '%')
                        ->orWhere('last_name', 'like', '%' . $request->data['name'] . '%');
                });
            }
            if (isset($request->data['branch']) && !empty($request->data['branch'])) {
                $motorPolicy->where('irss_branch_id', $request->data['branch']);
            }
            if (isset($request->data['company']) && !empty($request->data['company'])) {
                $motorPolicy->whereHas('company', function ($queryData) use ($request) {
                    $queryData->where('id', $request->data['company']);
                });
            }
            if (isset($request->data['cheque_no']) && !empty($request->data['cheque_no'])) {
                $motorPolicy->whereHas('payments', function ($query) use ($request) {
                    $query->where('payment_type', 2)->where('number', $request->data['cheque_no']);
                });
            }
            if (isset($request->data['inward_no']) && !empty($request->data['inward_no'])) {
                $motorPolicy->where('inward_no', $request->data['inward_no']);
            }
            if (isset($request->data['policy_no']) && !empty($request->data['policy_no'])) {
                $motorPolicy->where('policy_number', $request->data['policy_no']);
            }
            if (isset($request->data['product']) && !empty($request->data['product'])) {
                $motorPolicy->whereHas('product', function ($query) use ($request) {
                    $query->where('id', $request->data['product']);
                });
            }
            if (isset($request->data['engine_no']) && !empty($request->data['engine_no'])) {
                $motorPolicy->whereHas('motor_policy_vehicle', function ($query) use ($request) {
                    $query->where('engine_no', $request->data['engine_no']);
                });
            }
            if (isset($request->data['chasis_no']) && !empty($request->data['chasis_no'])) {
                $motorPolicy->whereHas('motor_policy_vehicle', function ($query) use ($request) {
                    $query->where('chasiss_no', $request->data['chasis_no']);
                });
            }
            if (isset($request->data['registration_no']) && !empty($request->data['registration_no'])) {
                $motorPolicy->whereHas('motor_policy_vehicle', function ($query) use ($request) {
                    $query->where('registration_no', realVehicleNo($request->data['registration_no']));
                });
            }
            if (isset($request->data['policy_start_date']) && !empty($request->data['policy_start_date']) && isset($request->data['policy_end_date']) && !empty($request->data['policy_end_date'])) {
                $motorPolicy->where('start_date', '<=', $request->data['policy_start_date'])->where('end_date', '>=', $request->data['policy_end_date']);
            } else {
                if (isset($request->data['policy_start_date']) && !empty($request->data['policy_start_date'])) {
                    $motorPolicy->where('start_date', $request->data['policy_start_date']);
                }
                if (isset($request->data['policy_end_date']) && !empty($request->data['policy_end_date'])) {
                    $motorPolicy->where('end_date', $request->data['policy_end_date']);
                }
            }

            if (isset($request->data['from_date']) && !empty($request->data['from_date']) && isset($request->data['end_date']) && !empty($request->data['end_date'])) {

                $from_date = Carbon::parse($request->data['from_date'])->format('Y-m-d 00:00:00');
                $to_date = Carbon::parse($request->data['end_date'])->format('Y-m-d 23:59:59');
                $motorPolicy->whereBetween('created_at', [$from_date, $to_date]);
            } else {
                if (isset($request->data['from_date']) && !empty($request->data['from_date'])) {
                    $from_date = Carbon::parse($request->data['from_date'])->format('Y-m-d 00:00:00');
                    $motorPolicy->where('created_at', $from_date);
                }
                if (isset($request->data['end_date']) && !empty($request->data['end_date'])) {
                    $to_date = Carbon::parse($request->data['end_date'])->format('Y-m-d 23:59:59');
                    $motorPolicy->where('created_at', $to_date);
                }
            }
        }
        $motorPolicy = $motorPolicy->latest()->get();

        if (!empty($motorPolicy) && sizeof($motorPolicy) != 0) {
            $myFile = Excel::raw(new ExportMotorPolicy($request->data), 'Xlsx');
            $response = array(
                'status' => 'true',
                'name' => "MotorPolicy_data.xlsx",
                'file' => "data:application/vnd.ms-excel;base64," . base64_encode($myFile),
            );
        } else {
            $response = array(
                'data' => $motorPolicy,
                'status' => false,
                'icon' => 'error',
                'msg' => "No Data available",
            );
        }
        // return Excel::download(new ExportMotorPolicy, 'motor_policy.xlsx');



        return  $response;
    }


    public function update_policy_export(Request $request)
    {
        $motorPolicy = motopolicy_data($request)->latest()->get(['id']);

        if (!empty($motorPolicy) && sizeof($motorPolicy) != 0) {
            $myFile = Excel::raw(new ExportUpdateMotorPolicy($request->data), 'Xlsx');
            $response = array(
                'status' => 'true',
                'name' => "PendingMotorPolicy_data.xlsx",
                'file' => "data:application/vnd.ms-excel;base64," . base64_encode($myFile),
            );
        } else {
            $response = array(
                'data' => $motorPolicy,
                'status' => false,
                'icon' => 'error',
                'msg' => "No Data available",
            );
        }
        return  $response;
    }
    /* sync policy */
    public function sync(Request $request)
    {
        try {
            $policy = $request->policy;
            $contents = Storage::allFiles('filezilla/' . $policy);
            $companies = Company::where('status', '=', 1)->get();
            $data = [];
            if (count($contents) > count($companies)) {
                foreach ($contents as $content) {
                    if (explode('.', explode("/", $content)[3])[0] != 'test') {
                        $data[] = explode('.', explode("/", $content)[3])[0];
                        Storage::has($content) ? Storage::move($content, str_replace("filezilla", "policies", $content)) : '';
                    }
                }
                if ($policy == 'motor') {
                    $motor_policies = MotorPolicy::where('status', 1)->with('company')->selectRaw("REPLACE(`policy_number`,'/', '') AS `number`,id,company_id,policy_copy,policy_number")->havingRaw('number IN ("' . implode('","', $data) . '")')->get();
                    foreach ($motor_policies as $motor_policy) {
                        $motor_policy->update(['policy_copy' => 'policies/motor/' . str_replace(" ", "_", $motor_policy->company->name) . '_' . $motor_policy->company->id . '/' . str_replace('/', '', $motor_policy->policy_number) . '.pdf', 'policy_copy_status' => 2]);
                    }
                } elseif ($policy == 'health') {
                    $health_policies = HealthPolicy::where('status', 1)->with('company')->selectRaw("REPLACE(`policy_number`,'/', '') AS `number`,id,company_id,policy_copy,policy_number")->havingRaw('number IN ("' . implode('","', $data) . '")')->get();
                    foreach ($health_policies as $health_policy) {
                        $health_policy->update(['policy_copy' => 'policies/health/' . str_replace(" ", "_", $health_policy->company->name) . '_' . $health_policy->company->id . '/' . str_replace('/', '', $health_policy->policy_number) . '.pdf', 'policy_copy_status' => 2]);
                    }
                } else {
                    $sme_policies = SmePolicy::where('status', 1)->with('company')->selectRaw("REPLACE(`policy_number`,'/', '') AS `number`,id,company_id,policy_copy,policy_number")->havingRaw('number IN ("' . implode('","', $data) . '")')->get();
                    foreach ($sme_policies as $sme_policy) {
                        $sme_policy->update(['policy_copy' => 'policies/sme/' . str_replace(" ", "_", $sme_policy->company->name) . '_' . $sme_policy->company->id . '/' . str_replace('/', '', $sme_policy->policy_number) . '.pdf', 'policy_copy_status' => 2]);
                    }
                }
            }
            $response = [
                'status' => true,
                'message' => ucfirst($policy) . 'Policy Sync Successfully.',
                'icon' => 'success',
            ];
        } catch (\Throwable $e) {
            dd($e);
            $response = [
                'status' => false,
                'message' => "Something Went Wrong! Please Try Again.",
                'icon' => 'error',
            ];
        }
        return response($response);
    }
    public function policy_download($id)
    {
        try {
            return Storage::download(decryptid($id));
        } catch (\Throwable $e) {
            dd($e);
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
            return response($response);
        }
    }
    public function cancel(Request $request)
    {
        try {
            $update = ['status' => 3, 'policy_cancel_reason' => $request->reason, 'policy_cancel_remark' => $request->remark];
            $motor_policy = MotorPolicy::where('id', decryptid($request->id));
            $motor_policy->update($update);
            MotorPolicyVehical::where('policy_id', decryptid($request->id))->update(['status' => 0]);
            MotorPolicyPayment::where('policy_id', decryptid($request->id))->update(['status' => 0]);
            $data['id'] = decryptid($request->id);
            $data['policy_type'] = 'MOTOR';
            $data['notification_type'] = 'book';
            $data['message'] = 'MOTOR Policy(' . $motor_policy->first()->inward_no . ') Cancelled!';
            Agent::find($motor_policy->first()->agent_id)->notify(new PolicyAddNotification($data));
            $response = [
                'status' => true,
                'message' => "Motor Policy " . $motor_policy->first()->inward_no . " Cancelled Successfully",
                'icon' => 'success',
            ];
        } catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => "Something Went Wrong! Please Try Again.",
                'icon' => 'error',
            ];
        }
        return response($response);
    }
    public function upload(Request $request)
    {
        try {
            $id = decryptid($request->id);
            $module = $request->module;
            if ($request->has('policy_copy')) {
                $policy = $this->getPolicy($module, $id);
                $url = 'policies/' . $module . '/' . str_replace(" ", "_", $policy->company->name) . '_' . $policy->company->id . '/' . str_replace('/', '', $policy->policy_number) . '.pdf';
                if (Storage::disk('s3')->exists($url))
                    Storage::disk('s3')->delete($url);
                Storage::disk('s3')->put($url, file_get_contents($request->policy_copy));
                $policy->update(['policy_copy' => $url, 'policy_copy_status' => 2]);
            }
            $response = [
                'status' => true,
                'message' => ucfirst($module) . " Policy Updated Successfully",
                'icon' => 'success',
            ];
        } catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => "Something Went Wrong! Please Try Again.",
                'icon' => 'error',
            ];
        }
        return response($response);
    }
    public function getPolicy($policy, $id)
    {
        if ($policy == 'motor') {
            $policy = MotorPolicy::where('id', $id)->with('company')->first();;
        } elseif ($policy == 'health') {
            $policy = HealthPolicy::where('id', $id)->with('company')->first();;
        } else {
            $policy = SmePolicy::where('id', $id)->with('company')->first();;
        }
        return $policy;
    }
    public function FodAgentListing(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
        $totalRecords = MotorPolicy::where('status', '!=', 2);
        $search_value=$request->search['value'];
        isset(Auth::guard('agent')->user()->id) ?
            $totalRecords->where('agent_id', Auth::guard('agent')->user()->id) :
            $totalRecords->whereHas('agent', function ($query) use ($request) {
                $query->where('fdo_id', Auth::guard('fdo')->user()->id);
            });
        $motorPolicy = MotorPolicy::where('status', '!=', 2)->with('customer', 'company', 'agent_only', 'motor_policy_vehicle_only','product');
        isset(Auth::guard('agent')->user()->id) ?
            $motorPolicy->where('agent_id', Auth::guard('agent')->user()->id) :
            $motorPolicy->whereHas('agent', function ($query) use ($request) {
                $query->where('fdo_id', Auth::guard('fdo')->user()->id);
            });
        if(!is_null($search_value)){
            $motorPolicy->whereHas('customer', function ($query) use ($search_value) {
                $query->where('first_name', 'like', '%' .$search_value . '%')->orWhere('middle_name', 'like', '%' .$search_value . '%')->orWhere('last_name', 'like', '%' .$search_value . '%');
            });
        }
        $totalRecordswithFilter = $motorPolicy->count();
        $motorPolicy = $motorPolicy
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $records = [];
        if (isset($motorPolicy) && !empty($motorPolicy)) {
            foreach ($motorPolicy as $key => $row) {
                $button = '';
                $button .= '<a href="' . route('fdo-agent.motor-policy.show', encryptid($row['id'])) . '"><button class="btn btn-success m-1 view-button">view</button></a>';
                $button .= $row->policy_copy_status == 2 ? '<a href="' . route('policy.download', encryptid($row->policy_copy)) . '" class="policy_copy_download"><button class="btn btn-success m-1 download-button">Download</button></a>':'';
                $records[] = array(
                    '5' => "<p class='customer-name'>".$row->customer->first_name . ' ' . $row->customer->middle_name . ' ' . $row->customer->last_name."</p>",
                    '0' => "<span class='title'><b>Policy No.: </b></span>".$row->policy_number,
                    '1' => !empty($row->motor_policy_vehicle_only) ? "<span class='title'><b>Vehicle No.: </b></span>".vehicleNO($row->motor_policy_vehicle_only->registration_no) : '',
                    '2' => !empty($row->product) ? "<span class='title'><b>Product Name: </b></span>".$row->product->name : '',
                    '3' => !empty($row->company) ? "<span class='title'><b>Company Name: </b></span>".getFirstString($row->company->name) : '',
                    '6' => "<span class='title'><b>Inward No.: </b></span>".$row->inward_no,
                    '7' => $button,
                    '8' =>$row->status
                );
            }
        }
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords->count(),
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $records
        );
        return response($response);
    }

    public function download_pdf()
    {
        $file = public_path().'/policies/motor/HealthPolicys.pdf';
        $headers = array('Content-Type: application/pdf',);
        return Response::download($file, 'policyDownload.pdf',$headers);
    }
}
