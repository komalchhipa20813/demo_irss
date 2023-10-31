<?php

namespace App\Http\Controllers;

use App\Models\{Agent, Company, Customer, Department, Designation, HealthPolicy, HealthPolicyPayment, IrssBranch, Product, Role, HealthPolicyMember, BranchImdName, MotorPolicy, Settings};
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Exports\{ExportHealthPolicy, ExportUpdateHealthPolicy};
use App\Notifications\PolicyAddNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class HealthPolicyController extends Controller
{
    // dashboard of health policy
    public function index()
    {
        $data['irss_branches'] = IrssBranch::where('status', 1)->get(['id', 'name']);
        $data['companies'] = Company::where('status', 1)->get(['id', 'name']);
        $data['products'] = Product::where('status', 1)->where('policy_type', 2)->get(['id', 'name']);
        $data['agents'] = Agent::where('status', 1)->get(['id', 'first_name', 'last_name', 'middle_name', 'prefix', 'code']);
        if (isset(Auth::user()->id))
            return view('pages.health-policy.index', compact('data'));
        else
            return view('pages.health-policy.indexAgent', compact('data'));
    }
    /* listing of health policy */
    public function listing(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
        $totalRecords = HealthPolicy::where('status', '!=', 2)->count();
        $users = HealthPolicy::query()->where('status', '!=', 2)->with('customer', 'company', 'agent_only');
        if ($request->ajax() && !empty($request->all())) {
            if (isset($request->agent)) {
                $users->where('agent_id', $request->agent);
            }
            if (isset($request->name)) {
                $users->whereHas('customer', function ($query) use ($request) {
                    $query->where('first_name', 'like', '%' . $request->name . '%')
                        ->orWhere('middle_name', 'like', '%' . $request->name . '%')
                        ->orWhere('last_name', 'like', '%' . $request->name . '%');
                });
            }
            if (isset($request->branch)) {
                $users->where('irss_branch_id', $request->branch);
            }
            if (isset($request->company)) {
                $users->whereHas('company_branch', function ($query) use ($request) {
                    $query->whereHas('company', function ($queryData) use ($request) {
                        $queryData->where('id', $request->company);
                    });
                });
            }
            if (isset($request->cheque_no)) {
                $users->whereHas('health_policy_payments', function ($query) use ($request) {
                    $query->where('payment_type', 2)->where('number', $request->cheque_no);
                });
            }
            if (isset($request->inward_no)) {
                $users->where('inward_no', 'like', '%' . $request->inward_no . '%');
            }
            if (isset($request->policy_no)) {
                $users->where('policy_number', $request->policy_no);
            }
            if (isset($request->product)) {
                $users->whereHas('product', function ($query) use ($request) {
                    $query->where('id', $request->product);
                });
            }

            if (isset($request->policy_start_date) && isset($request->policy_end_date)) {
                $users->where('start_date', '>=', $request->policy_start_date)->where('end_date', '<=', $request->policy_end_date);
            } else {
                if (isset($request->policy_start_date)) {
                    $users->where('start_date', $request->policy_start_date);
                }
                if (isset($request->policy_end_date)) {
                    $users->where('end_date', $request->policy_end_date);
                }
            }

            if (isset($request->from_date) && isset($request->end_date)) {

                $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
                $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                $users->whereBetween('created_at', [$from_date, $to_date]);
            } else {
                if (isset($request->from_date)) {
                    $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
                    $users->where('created_at', $from_date);
                }
                if (isset($request->end_date)) {
                    $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                    $users->where('created_at', $to_date);
                }
            }
        }

        if (!isset(Auth::user()->id)) {
            isset(Auth::guard('agent')->user()->id) ?
                $users->where('agent_id', Auth::guard('agent')->user()->id) :
                $users->whereHas('agent', function ($query) use ($request) {
                    $query->where('fdo_id', Auth::guard('fdo')->user()->id);
                });
        }
        $totalRecordswithFilter = $users->count();
        $users = $users
            ->skip($start)
            ->take($rowperpage)
            ->get(['id', 'product_id', 'customer_id', 'company_branch_id', 'agent_id', 'policy_number', 'inward_no', 'policy_status', 'company_id', 'policy_copy_status', 'policy_copy', 'status']);
        $records = [];
        $permissionList = isset(Auth::user()->id) ? permission() : '';
        if (isset($users) && !empty($users)) {
            foreach ($users as $key => $row) {
                $button = '';

                if (isset(Auth::user()->id) && in_array("101", $permissionList)) {
                    $button .= '<a href="' . route('health-policy.show', encryptid($row['id'])) . '"><button class="btn btn-sm btn-success m-1"  data-id="' . encryptid($row['id']) . '" >
                    <i class="mdi mdi-view-module"></i>
                    </button></a>';
                }
                if (isset(Auth::user()->id) && in_array("103", $permissionList)) {
                    $button .= '<a href="' . route('health-policy.edit', encryptid($row['id'])) . '"><button class=" btn btn-sm btn-success m-1">
                    <i class="mdi mdi-square-edit-outline"></i>
                    </button></a>';
                }
                if (isset(Auth::user()->id) && in_array("104", $permissionList)) {
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
                    '1' => 'HEALTH',
                    '2' => !empty($row->company) ? getFirstString($row->company->name) : '',
                    '3' => !empty($row->agent_only) ? $row->agent_only->code : '',
                    '4' => $row->customer->first_name . ' ' . $row->customer->middle_name . ' ' . $row->customer->last_name,
                    '5' => $row->inward_no,
                    '6' => $button,
                    '7' => $row->status
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

    /* Check The Policy Number In Database */
    public function policy_number_check(Request $request)
    {
        if (isset($request) && $request->id && $request->policy_number) {
            $policy = HealthPolicy::where('policy_number', $request->policy_number)->where('status', 1)->where('id', '!=', decryptid($request->id))->first(['id']);
            return !is_null($policy) ? true : false;
        } else {
            return false;
        }
    }

    // redirect to create page
    public function create()
    {
        session()->forget('policy_members');
        try {
            $data['roles'] = Role::where('status', 1)->where('id', '!=', 1)->get(['id', 'title']);
            $data['departments'] = Department::where('status', 1)->get(['id', 'name']);
            $data['designations'] = Designation::where('status', 1)->get(['id', 'name']);
            $data['products'] = Product::where('status', 1)->where('policy_type', 2)->get(['id', 'name']);
            $data['agents'] = Agent::where('status', 1)->get(['id', 'first_name', 'last_name', 'middle_name', 'prefix', 'code']);
            $data['customers'] = Customer::where('status', 1)->get(['id', 'first_name', 'last_name', 'middle_name', 'prefix', 'customer_code']);
            $data['irss_branches'] = IrssBranch::where('status', 1)->get(['id', 'name']);
            $data['companies'] = Company::where('status', 1)->get(['id', 'name']);
            $data['policy'] = null;
            return view('pages.health-policy.create', compact('data'));
        } catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
            return response($response);
        }
    }
    //Storing And Updating Data Of Health Policy
    public function store(Request $request)
    {
        $request->validate(
            [
                'product' => 'required',
                'branch' => 'required',
                'business_date' => 'required',
                'customer' => 'required',
                'agent' => 'required',
                'company' => 'required',
                'company_branch_name' => 'required',
                'branch_imd' => 'required',
                'sub_product' => 'required',
                'product_type' => 'required',
                'sum_insured' => 'required',
                'od' => 'required',
                'gst' => 'required',
                'payment_type' => 'required',
                "policy_copy" => "sometimes|required|max:10000"
            ]
        );
        try {
            $policy_url = HealthPolicy::find(decryptid($request->policy_id));
            $url = !empty($policy_url->policy_copy) ? $policy_url->policy_copy : null;
            $policy_copy_status = !empty($policy_url->policy_copy_status) ? $policy_url->policy_copy_status : 1;
            if ($request->has('policy_number') && $request->has('policy_copy')) {
                $company = Company::find($request->company);
                $url = 'policies/health/' . str_replace(" ", "_", $company->name) . '_' . $company->id . '/' . str_replace('/', '', $request->policy_number) . '.pdf';
                if (Storage::disk('s3')->exists($url))
                    Storage::disk('s3')->delete($url);
                Storage::disk('s3')->put($url, file_get_contents($request->policy_copy));
                $policy_copy_status = 2;
            }
            if (($request->policy_type == 'renewal' || $request->policy_type == 'port renewal') && isset($request->renew_pre_policy_number)) {
                $renewal_previous_policy_number = $request->renew_pre_policy_number;
            } else {
                $renewal_previous_policy_number = null;
            }
            if (($request->policy_type == 'renewal' || $request->policy_type == 'port renewal') && isset($request->previouspolicycompany)) {
                $renewal_previous_company_id = $request->previouspolicycompany;
            } else {
                $renewal_previous_company_id = null;
            }

            $health_policy = HealthPolicy::updateOrCreate([
                'id' => decryptid($request->policy_id),
            ], [
                "policy_type" => $request->policy_type,
                "product_id" => $request->product,
                "customer_id" => $request->customer,
                "irss_branch_id" => $request->branch,
                'code_type' => $request->code_type,
                'edited_by_id'=>(decryptid($request->policy_id) != 0) ? Auth::user()->id : null,
                'edited_at'=>(decryptid($request->policy_id) != 0) ?  Carbon::now()->format('Y-m-d H:i:s'):null,
                "company_id" => $request->company,
                "company_branch_id" => $request->company_branch_name,
                "branch_imd_id" => $request->branch_imd,
                "sub_product_id" => $request->sub_product,
                "product_type" => $request->product_type,
                "business_date" => $request->business_date,
                "agent_id" => $request->agent,
                "policy_copy" => $url,
                "policy_number" => $request->policy_number,
                "has_previous_policy" => isset($request->has_policy) ? ($request->has_policy == 'on' ? 2 : 1) : 1,
                "previous_policy_number" => isset($request->has_policy) ? ($request->has_policy == 'on' ? $request->pre_policy_number : null) : null,
                "previous_start_date" => isset($request->has_policy) ? ($request->has_policy == 'on' ? $request->pre_start_date : null) : null,
                "previous_end_date" => isset($request->has_policy) ? ($request->has_policy == 'on' ? $request->pre_end_date : null) : null,
                "previous_company_id" => isset($request->has_policy) ? ($request->has_policy == 'on' ? $request->pre_company : null) : null,
                'renewal_previous_policy_number' => $renewal_previous_policy_number,
                "renewal_previous_company_id" => $renewal_previous_company_id,
                "issue_date" => $request->issue_date,
                "start_date" => $request->start_date,
                "end_date" => $request->end_date,
                "proposal_dob" => $request->proposal_dob,
                "policy_tenure" => $request->policy_tenure,
                "sum_insured" => $request->sum_insured,
                "od" => $request->od,
                "stamp_duty" => $request->stamp_duty,
                "is_gst_value" => isset($request->chkgstvalue) ? 2 : 1,
                "gst" => $request->gst,
                "total_premium" => $request->total_premium,
                "remark" => $request->remark,
                "add_member" => isset($request->add_member) ? 0 : 1,
                'policy_copy_status' => $policy_copy_status,
            ]);
            // inward number
            $irss_branch = IrssBranch::where('id', $request->branch)->with('city')->first(['id', 'city_id', 'policy_inward_code']);
            /* inward number */
            $settings = Settings::where('key', 'policy_no');
            $inward_number = inwardFirstChar($request->policy_type) . str_pad($request->branch, 2, '0', STR_PAD_LEFT) . str_replace('-', '', $irss_branch->city->rto_code) . Carbon::now()->format('y') . $irss_branch->policy_inward_code . $settings->first()->value;
            /* insert inward or delete payments */
            if (decryptid($request->policy_id) != 0) {
                HealthPolicyPayment::where('policy_id', decryptid($request->policy_id))->delete();
                HealthPolicyMember::where('health_policy_id', decryptid($request->policy_id))->delete();
            } else {
                $settings->update(['value' => str_pad(++$settings->first()->value, 6, '0', STR_PAD_LEFT)]);
                $health_policy->update(['inward_no' => $inward_number,'created_by_id' => Auth::user()->id]);
            }
            //setup payment data by payment type
            $paymentData = get_payment_field($health_policy->id, $request);
            HealthPolicyPayment::insert($paymentData);
            /* store members */
            if (!empty(session('policy_members'))) {
                foreach (session('policy_members') as $key => $member) {
                    $policy_members[] = array(
                        'health_policy_id' => $health_policy->id,
                        'relation' => $member['relation'],
                        'name' => $member['name'],
                        'sum_insured' => $member['sum_insured'],
                        'dob' => $member['dob'],
                    );
                }
                HealthPolicyMember::insert($policy_members);
            }

            $response = [
                'status' => true,
                'message' => 'Health Policy ' . $health_policy->inward_no . (decryptid($request->policy_id) == 0 ? ' Created' : ' Updated') . ' Successfully.',
                'redirect_url' => 'health-policy/create',
                'icon' => 'success',
            ];
            $health_policy = $health_policy->toArray();
            $health_policy['policy_type'] = 'HEALTH';
            $health_policy['notification_type'] = 'book';
            $health_policy['message'] = (decryptid($request->policy_id) == 0 ? 'New ' : 'Updated ') . 'Health Policy(' . $health_policy['inward_no'] . ') Recieved!';
            Agent::find($request->agent)->notify(new PolicyAddNotification($health_policy));
        } catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'redirect_url' => 'health-policy',
                'icon' => 'error',
            ];
        }
        return response($response);
    }
    /* Show Data Of Health Policy*/
    public function show($id)
    {
        try {
            $id = decryptid($id);
            $getPolicy = HealthPolicy::with(['payments', 'customer','company', 'company_branch', 'branch_imd_name', 'branch', 'product', 'agent', 'created_by', 'updated_by','edited_by', 'previous_company', 'renewal_previous_company'])->where('id', $id)->first();
            $payment = $getPolicy->payments()->with('bank')->first();

            $od=(!empty($getPolicy->od))?$getPolicy->od :0;
            $add_on=0;
            $total_od=round($od + $add_on);
            $tp=0;
            $pay_to_owner=0;
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
                return view('pages.health-policy.show', compact(['getPolicy', 'payment','data']));
            }
        } catch (\Throwable $e) {
            return redirect('/');
        }
    }
    /* update health policy */
    public function edit($id)
    {
        try {
            session()->forget('policy_members');
            $data['roles'] = Role::where('status', 1)->where('id', '!=', 1)->get(['id', 'title']);
            $data['departments'] = Department::where('status', 1)->get(['id', 'name']);
            $data['designations'] = Designation::where('status', 1)->get(['id', 'name']);
            $data['products'] = Product::where('status', 1)->where('policy_type', 2)->get(['id', 'name']);
            $data['agents'] = Agent::where('status', 1)->get(['id', 'first_name', 'last_name', 'middle_name', 'prefix', 'code']);
            $data['customers'] = Customer::where('status', 1)->get(['id', 'first_name', 'last_name', 'middle_name', 'prefix', 'customer_code']);
            $data['irss_branches'] = IrssBranch::where('status', 1)->get(['id', 'name']);
            $data['companies'] = Company::where('status', 1)->get(['id', 'name']);
            $data['policy'] = HealthPolicy::find(decryptid($id));
            $data['payment_type'] = HealthPolicyPayment::where('policy_id', decryptid($id))->first(['id', 'payment_type']);
            return view('pages.health-policy.create', compact('data'));
        } catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
            return response($response);
        }
    }
    /*Display Payment Data of Health Policy*/
    public function get_payment_data(Request $request)
    {
        try {
            $data['payments'] = HealthPolicyPayment::where('policy_id', decryptid($request->policy_id))->get()->toArray();

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
    //Remove Data Of Health Policy
    public function destroy($id)
    {
        try {
            $update = ['status' => 2];
            $health_policy = HealthPolicy::where('id', decryptid($id));
            $health_policy->update($update);
            $health_policy = $health_policy->first();
            $health_policy->policy_type = 'HEALTH';
            $health_policy->notification_type = 'book';
            $health_policy->message = 'Health Policy(' . $health_policy->inward_no . ') Deleted!';
            Agent::find($health_policy->agent_id)->notify(new PolicyAddNotification($health_policy));
            $response = [
                'status' => true,
                'message' => "Health Policy " . $health_policy->inward_no . " Data Deleted Successfully",
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
    public function policy_update()
    {
        $data['irss_branches'] = IrssBranch::where('status', 1)->get(['id', 'name']);
        $data['companies'] = Company::where('status', 1)->get(['id', 'name']);
        $data['products'] = Product::where('status', 1)->where('policy_type', 2)->get(['id', 'name']);
        $data['agents'] = Agent::where('status', 1)->get(['id', 'first_name', 'last_name', 'middle_name', 'prefix', 'code']);
        return view('pages.update-all-policy.update_health_policy', compact('data'));
    }
    public function update_policy_listing(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
        $totalRecords = HealthPolicy::where('status', '!=', 2)->where('policy_number', null)->count();
        $users = healthpolicy_data($request);
        $totalRecordswithFilter = $users->count();
        $users = $users
            ->skip($start)
            ->take($rowperpage)
            ->get(['id', 'product_id', 'customer_id', 'company_branch_id', 'agent_id', 'policy_number', 'inward_no', 'company_id', 'policy_tenure', 'branch_imd_id', 'issue_date', 'start_date', 'end_date', 'total_premium', 'od']);
        $records = [];
        $tenures = tenures();
        if (isset($users) && !empty($users)) {
            $sr = 1;
            foreach ($users as $key => $row) {

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
                    '0' => '<input type="checkbox" class="checkbox"  name="checkid[]" id="' . encryptid($row->id) . '"  onclick="single_unselected(this);" data-series="' . $sr . '"   style="    margin-left: 8px;"/><input type="hidden" name="id_' . $sr . '" value="' . encryptid($row->id) . '"><input type="hidden" name="policy_name_' . $sr . '" value="health">',
                    '1' => '<select class="form-select form-control branch_imd "  name="branch_imd_' . $sr . '" >' . $html . ' </select>',
                    '2' => '- ' . $row->inward_no . '<br>- ' . ((!empty($row->company_id)) ?$row->company->name : '' ) . '<br>- ' . ((!empty($row->agent_id)) ? $row->agent->code  : '' ),
                    '3' => (!empty($row->product)) ? $row->product->name : '',
                    '4' => '<p>' . $row->od . '</p>',
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
                    '10' => '<input type="text" class="form-control" name="policy_number_' . $sr . '" id="policy_number"  autocomplete="off"  value="' . $row->policy_number . '">',
                    '11' => '<input class="form-control" name="policy_copy_' . $sr . '" type="file" id="policy_copy" accept="application/pdf">',
                    '12' => (!empty($row->customer)) ? ($row->customer->prefix . ' ' . $row->customer->first_name . ' ' . $row->customer->last_name ) : '',
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

    /* Storing Data Of Add Member to session */
    public function store_member(Request $request)
    {
        try {
            $request->validate(
                [
                    'relation' => 'required',
                    'name' => 'required',
                    'birthdate' => 'required',
                    'sum_insured' => 'required',
                ]
            );
            if (empty(session('policy_members'))) {
                $id = 1;
            } else {
                foreach (session('policy_members') as $key => $value) {
                    $i = $value['id'];
                }
                $id = $i + 1;
            }
            $members = session()->get('policy_members', []);
            $memberData = [
                "id" => $id,
                'relation' => $request->relation,
                'name' => $request->name,
                'sum_insured' => $request->sum_insured,
                'dob' => $request->birthdate,
                'age' => $request->age
            ];
            $members[$id] = $memberData;
            session()->put('policy_members', $members);
            $response = [
                'data' => $memberData,
                'status' => true,
                'message' => 'Add Member Successfully.',
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
    /* listing of health policy add member data */
    public function add_member_listing(Request $request)
    {
        try {
            $policy_members = session('policy_members');
            $records = [];
            if (!is_null($policy_members)) {
                foreach ($policy_members as $key => $row) {
                    $button = '';
                    if (in_array("104", permission())) {
                        $button .= '<button class="member_delete btn btn-sm btn-danger m-1" data-id="' . encryptid($row['id']) . '">
                        <i class="mdi mdi-delete"></i>
                        </button>';
                    }
                    $records[] = array(
                        '0' => $key,
                        '1' => $row['relation'],
                        '2' => $row['name'],
                        '3' => $row['sum_insured'],
                        '4' => $row['dob'],
                        '5' => $row['age'],
                        '6' => $button
                    );
                }
            }
            return response(['data' => $records]);
        } catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => "Something Went Wrong! Please Try Again.",
                'icon' => 'error',
            ];
            return response($response);
        }
    }
    /* Remove Data Of Add Member */
    public function delete($id)
    {
        try {
            $policy_members = session('policy_members');
            unset($policy_members[decryptid($id)]);
            session()->put('policy_members', $policy_members);
            $response = [
                'status' => true,
                'message' => "Member Data Deleted Successfully",
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
    /* get Data Of Add Member */
    public function get_member_data($id)
    {
        try {
            $members = HealthPolicyMember::where('health_policy_id', decryptid($id))->get();
            $memberData = session()->get('policy_members', []);
            foreach ($members as $key => $member) {
                $memberData[$key + 1] = [
                    "id" => $key + 1,
                    'relation' => $member->relation,
                    'name' => $member->name,
                    'sum_insured' => $member->sum_insured,
                    'dob' => $member->dob,
                    'age' => age($member->dob)
                ];
            }
            session()->put('policy_members', $memberData);
            return response(true);
        } catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => "Something Went Wrong! Please Try Again.",
                'icon' => 'error',
            ];
            return response($response);
        }
    }

    public function export(Request $request)
    {

        $users = HealthPolicy::query()->where('status', '!=', 2)->with('product', 'customer', 'company_branch', 'company', 'payments', 'agent');
        if ($request->ajax() && !empty($request->all())) {
            if (isset($request->data['agent']) && !empty($request->data['agent'])) {
                $users->where('agent_id', $request->data['agent']);
            }
            if (isset($request->data['name']) && !empty($request->data['name'])) {
                $users->whereHas('customer', function ($query) use ($request) {
                    $query->where('first_name', 'like', '%' . $request->data['name'] . '%')
                        ->orWhere('middle_name', 'like', '%' . $request->data['name'] . '%')
                        ->orWhere('last_name', 'like', '%' . $request->data['name'] . '%');
                });
            }
            if (isset($request->data['branch']) && !empty($request->data['branch'])) {
                $users->where('irss_branch_id', $request->data['branch']);
            }
            if (isset($request->data['company']) && !empty($request->data['company'])) {
                $users->whereHas('company', function ($queryData) use ($request) {
                    $queryData->where('id', $request->data['company']);
                });
            }
            if (isset($request->data['cheque_no']) && !empty($request->data['cheque_no'])) {
                $users->whereHas('payments', function ($query) use ($request) {
                    $query->where('payment_type', 2)->where('number', $request->data['cheque_no']);
                });
            }
            if (isset($request->data['inward_no']) && !empty($request->data['inward_no'])) {
                $users->where('inward_no', $request->data['inward_no']);
            }
            if (isset($request->data['policy_no']) && !empty($request->data['policy_no'])) {
                $users->where('policy_number', $request->data['policy_no']);
            }
            if (isset($request->data['product']) && !empty($request->data['product'])) {
                $users->whereHas('product', function ($query) use ($request) {
                    $query->where('id', $request->data['product']);
                });
            }
            if (isset($request->data['policy_start_date']) && !empty($request->data['policy_start_date']) && isset($request->data['policy_end_date']) && !empty($request->data['policy_end_date'])) {
                $users->where('start_date', '<=', $request->data['policy_start_date'])->where('end_date', '>=', $request->data['policy_end_date']);
            } else {
                if (isset($request->data['policy_start_date']) && !empty($request->data['policy_start_date'])) {
                    $users->where('start_date', $request->data['policy_start_date']);
                }
                if (isset($request->data['policy_end_date']) && !empty($request->data['policy_end_date'])) {
                    $users->where('end_date', $request->data['policy_end_date']);
                }
            }

            if (isset($request->data['from_date']) && !empty($request->data['from_date']) && isset($request->data['end_date']) && !empty($request->data['end_date'])) {
                $from_date = Carbon::parse($request->data['from_date'])->format('Y-m-d 00:00:00');
                $to_date = Carbon::parse($request->data['end_date'])->format('Y-m-d 23:59:59');
                $users->whereBetween('created_at', [$from_date, $to_date]);
            } else {
                if (isset($request->data['from_date']) && !empty($request->data['from_date'])) {
                    $from_date = Carbon::parse($request->data['from_date'])->format('Y-m-d 00:00:00');
                    $users->where('created_at', $from_date);
                }
                if (isset($request->data['end_date']) && !empty($request->data['end_date'])) {
                    $to_date = Carbon::parse($request->data['end_date'])->format('Y-m-d 23:59:59');
                    $users->where('created_at', $to_date);
                }
            }
        }

        $users = $users->latest()->get(['id', 'product_id', 'customer_id', 'company_branch_id', 'agent_id', 'policy_number', 'inward_no', 'policy_status', 'company_id', 'policy_copy_status', 'policy_copy', 'status']);

        if (!empty($users) && sizeof($users) != 0) {
            $myFile = Excel::raw(new ExportHealthPolicy($request->data), 'Xlsx');
            $response = array(
                'status' => 'true',
                'name' => "HealthPolicy_data.xlsx",
                'file' => "data:application/vnd.ms-excel;base64," . base64_encode($myFile),
            );
        } else {
            $response = array(
                'status' => false,
                'icon' => 'error',
                'msg' => "No Data available",
            );
        }
        return $response;
    }

    public function update_policy_export(Request $request)
    {

        $users = healthpolicy_data($request)->latest()->get(['id']);
        if (!empty($users) && sizeof($users) != 0) {
            $myFile = Excel::raw(new ExportUpdateHealthPolicy($request->data), 'Xlsx');
            $response = array(
                'status' => 'true',
                'name' => "PendingHealthPolicy_data.xlsx",
                'file' => "data:application/vnd.ms-excel;base64," . base64_encode($myFile),
            );
        } else {
            $response = array(
                'status' => false,
                'icon' => 'error',
                'msg' => "No Data available",
            );
        }
        return $response;
    }
    public function cancel(Request $request)
    {
        try {
            $update = ['status' => 3, 'policy_cancel_reason' => $request->reason, 'policy_cancel_remark' => $request->remark];
            $health_policy = HealthPolicy::where('id', decryptid($request->id));
            $health_policy->update($update);
            $data['id'] = decryptid($request->id);
            $data['policy_type'] = 'HEALTH';
            $data['notification_type'] = 'book';
            $data['message'] = 'Health Policy(' . $health_policy->first()->inward_no . ') Cancelled!';
            Agent::find($health_policy->first()->agent_id)->notify(new PolicyAddNotification($data));
            $response = [
                'status' => true,
                'message' => "Health Policy " . $health_policy->first()->inward_no . " Cancelled Successfully",
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
    public function FodAgentListing(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
        $search_value = $request->search['value'];
        $totalRecords = HealthPolicy::where('status', '!=', 2);
        isset(Auth::guard('agent')->user()->id) ?
            $totalRecords->where('agent_id', Auth::guard('agent')->user()->id) :
            $totalRecords->whereHas('agent', function ($query) use ($request) {
                $query->where('fdo_id', Auth::guard('fdo')->user()->id);
            });
        $users = HealthPolicy::query()->where('status', '!=', 2)->with('customer', 'company', 'agent_only', 'product');
        isset(Auth::guard('agent')->user()->id) ?
            $users->where('agent_id', Auth::guard('agent')->user()->id) :
            $users->whereHas('agent', function ($query) use ($request) {
                $query->where('fdo_id', Auth::guard('fdo')->user()->id);
            });

        isset(Auth::guard('agent')->user()->id) ?
            $users->where('agent_id', Auth::guard('agent')->user()->id) :
            $users->whereHas('agent', function ($query) use ($request) {
                $query->where('fdo_id', Auth::guard('fdo')->user()->id);
            });
        if (!is_null($search_value)) {
            $users->whereHas('customer', function ($query) use ($search_value) {
                $query->where('first_name', 'like', '%' . $search_value . '%')->orWhere('middle_name', 'like', '%' . $search_value . '%')->orWhere('last_name', 'like', '%' . $search_value . '%');
            });
        }
        $totalRecordswithFilter = $users->count();
        $users = $users
            ->skip($start)
            ->take($rowperpage)
            ->get(['id', 'product_id', 'customer_id', 'company_branch_id', 'agent_id', 'policy_number', 'inward_no', 'policy_status', 'company_id', 'policy_copy_status', 'policy_copy', 'status']);
        $records = [];
        if (isset($users) && !empty($users)) {
            foreach ($users as $key => $row) {
                $button = '';
                $button .= '<a href="' . route('fdo-agent.health-policy.show', encryptid($row['id'])) . '"><button class="btn btn-sm btn-success m-1 view-button">view</button></a>';
                $button .= $row->policy_copy_status == 2 ? '<a href="' . route('policy.download', encryptid($row->policy_copy)) . '" class="policy_copy_download"><button class="btn btn-success m-1 download-button">Download</button></a>' : '';
                $records[] = array(
                    '4' => "<p class='customer-name'>".$row->customer->first_name . ' ' . $row->customer->middle_name . ' ' . $row->customer->last_name."</p>",
                    '0' => "<span class='title'><b>Policy No.: </b></span>" . $row->policy_number,
                    '1' => !empty($row->product) ? "<span class='title'><b>Product Name: </b></span>" . $row->product->name : '',
                    '2' => !empty($row->company) ? "<span class='title'><b>Company Name: </b></span>" . getFirstString($row->company->name) : '',
                    '5' => "<span class='title'><b>Inward No.: </b></span>" . $row->inward_no,
                    '6' => $button,
                    '7' => $row->status
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
}
