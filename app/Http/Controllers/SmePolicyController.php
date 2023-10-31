<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Agent, Company, CompanyBranch, Customer, HealthPolicy, IrssBranch, Product, BranchImdName, CoSharingData, MotorPolicy, Settings, SmePolicy, SmePolicyPayment, SubProduct};
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\Exports\{ExportSmePolicy, ExportUpdateSMEPolicy};
use App\Notifications\PolicyAddNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;


class SmePolicyController extends Controller
{

    /*Dashboard Of SME Policy*/
    public function index()
    {
        $data['irss_branches'] = IrssBranch::where('status', 1)->get(['id', 'name']);
        $data['companies'] = Company::where('status', 1)->get(['id', 'name']);
        $data['products'] = Product::where('status', 1)->where('policy_type', 3)->get(['id', 'name']);
        $data['agents'] = Agent::where('status', 1)->get(['id', 'first_name', 'last_name', 'middle_name', 'prefix', 'code']);
        if (isset(Auth::user()->id))
            return view('pages.sme-policy.index', compact('data'));
        else
            return view('pages.sme-policy.indexAgent', compact('data'));
    }

    /* listing of sme policy */
    public function listing(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
        $totalRecords = SmePolicy::where('status', '!=', 2)->count();
        $sme_policies = SmePolicy::where('status', '!=', 2)->with('customer', 'agent', 'company');
        if ($request->ajax() && !empty($request->all())) {
            if (isset($request->agent)) {
                $sme_policies->where('agent_id', $request->agent);
            }
            if (isset($request->name)) {
                $sme_policies->whereHas('customer', function ($query) use ($request) {
                    $query->where('first_name', 'like', '%' . $request->name . '%')
                        ->orWhere('middle_name', 'like', '%' . $request->name . '%')
                        ->orWhere('last_name', 'like', '%' . $request->name . '%');
                });
            }
            if (isset($request->branch)) {
                $sme_policies->where('irss_branch_id', $request->branch);
            }
            if (isset($request->company)) {
                $sme_policies->where('company_id', $request->company);
            }
            if (isset($request->cheque_no)) {
                $sme_policies->whereHas('payments', function ($query) use ($request) {
                    $query->where('payment_type', 2)->where('number', $request->cheque_no);
                });
            }
            if (isset($request->inward_no)) {
                $sme_policies->where('inward_no', 'like', '%' . $request->inward_no . '%');
            }
            if (isset($request->policy_no)) {
                $sme_policies->where('policy_number', $request->policy_no);
            }
            if (isset($request->product)) {
                $sme_policies->whereHas('product', function ($query) use ($request) {
                    $query->where('id', $request->product);
                });
            }

            if (isset($request->policy_start_date) && isset($request->policy_end_date)) {
                $sme_policies->where('start_date', '>=', $request->policy_start_date)->where('end_date', '<=', $request->policy_end_date);
            } else {
                if (isset($request->policy_start_date)) {
                    $sme_policies->where('start_date', $request->policy_start_date);
                }
                if (isset($request->policy_end_date)) {
                    $sme_policies->where('end_date', $request->policy_end_date);
                }
            }

            if (isset($request->from_date) && isset($request->end_date)) {

                $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
                $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                $sme_policies->whereBetween('created_at', [$from_date, $to_date]);
            } else {
                if (isset($request->from_date)) {
                    $from_date = Carbon::parse($request->from_date)->format('Y-m-d 00:00:00');
                    $sme_policies->where('created_at', $from_date);
                }
                if (isset($request->end_date)) {
                    $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                    $sme_policies->where('created_at', $to_date);
                }
            }
        }
        if (!isset(Auth::user()->id)) {
            isset(Auth::guard('agent')->user()->id) ?
                $sme_policies->where('agent_id', Auth::guard('agent')->user()->id) :
                $sme_policies->whereHas('agent', function ($query) use ($request) {
                    $query->where('fdo_id', Auth::guard('fdo')->user()->id);
                });
        }
        $totalRecordswithFilter = $sme_policies->count();
        $sme_policies = $sme_policies
            ->skip($start)
            ->take($rowperpage)
            ->get(['id', 'product_id', 'customer_id', 'company_branch_id', 'inward_no', 'policy_status', 'agent_id', 'company_id', 'policy_number', 'policy_copy_status', 'policy_copy', 'status']);
        $records = [];
        $permissionList = isset(Auth::user()->id) ? permission() : '';
        if (isset($sme_policies) && !empty($sme_policies)) {
            foreach ($sme_policies as $key => $row) {
                $button = '';

                if (isset(Auth::user()->id) && in_array("113", $permissionList)) {
                    $button .= '<a href="' . route('sme-policy.show', encryptid($row['id'])) . '"><button class="btn btn-sm btn-success m-1"  data-id="' . encryptid($row['id']) . '" >
                    <i class="mdi mdi-view-module"></i>
                    </button></a>';
                }
                if (isset(Auth::user()->id) && in_array("115", $permissionList)) {
                    $button .= '<a href="' . route('sme-policy.edit', encryptid($row['id'])) . '"><button class=" btn btn-sm btn-success m-1">
                    <i class="mdi mdi-square-edit-outline"></i>
                    </button></a>';
                }
                if (isset(Auth::user()->id) && in_array("116", $permissionList)) {
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
                    '1' => 'SME',
                    '2' => !empty($row->company) ? getFirstString($row->company->name) : '',
                    '3' => !empty($row->agent) ? $row->agent->code : '',
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
            $policy = SmePolicy::where('policy_number', $request->policy_number)->where('status', 1)->where('id', '!=', decryptid($request->id))->first(['id']);
            return !is_null($policy) ? true : false;
        } else {
            return false;
        }
    }

    /* Redirect To Create Page */
    public function create()
    {
        session()->forget('co_sharing_detail');
        try {
            $data['products'] = Product::where('status', 1)->where('policy_type', 3)->get(['id', 'name']);
            $data['customers'] = Customer::where('status', 1)->get(['id', 'first_name', 'last_name', 'middle_name', 'prefix', 'customer_code']);
            $data['agents'] = Agent::where('status', 1)->get(['id', 'first_name', 'last_name', 'middle_name', 'prefix', 'code']);
            $data['irss_branches'] = IrssBranch::where('status', 1)->get(['id', 'name']);
            $data['companies'] = Company::where('status', 1)->get(['id', 'name']);
            $data['company_branchs'] = CompanyBranch::where('status', 1)->get(['id', 'name']);
            $data['branch_imd'] = BranchImdName::where('status', 1)->get(['id', 'name']);
            $data['sub_products'] = SubProduct::where('status', 1)->get(['id', 'name']);
            $data['policy'] = null;
            return view('pages.sme-policy.create', compact('data'));
        } catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
            return response($response);
        }
    }

    /* Store Co-Sharing Detail  Data */
    public function co_sharing_data(Request $request)
    {
        try {
            $request->validate(
                [
                    'cosharecompany' => 'required',
                    'share' => 'required',
                ]
            );
            $co_sharing_detail = session('co_sharing_detail');
            $data = [];
            if (!is_null($co_sharing_detail)) {
                foreach ($co_sharing_detail as $co_sharing) {
                    $data[] = $co_sharing['share'];
                }
            }
            if (array_sum($data) + $request->share <= 100) {
                if (empty(session('co_sharing_detail'))) {
                    $id = 1;
                } else {
                    foreach (session('co_sharing_detail') as $key => $value) {
                        $i = $value['id'];
                    }
                    $id = $i + 1;
                }

                $co_sharing = session()->get('co_sharing_detail', []);

                $co_sharing[$id] = [
                    "id" => $id,
                    'company_name' => $request->company_name,
                    'company_id' => $request->cosharecompany,
                    'type' => 'Follower',
                    'share' => $request->share,
                    'policy_premium' => $request->Bpolicyprm,
                    'policy_terrorism_premium' => $request->Bpolicyterrorism,
                ];
                session()->put('co_sharing_detail', $co_sharing);
                $data = $co_sharing[$id];
                $data['flag'] = 1;
                $response = [
                    'status' => true,
                    'message' => 'Co-Sharing Detail Data Created Successfully',
                    'icon' => 'success',
                    'data' => $data
                ];
            } else {
                $response = [
                    'status' => false,
                    'message' => 'Share Of Co-Sharing Is Not Valid.',
                    'icon' => 'info',
                ];
            }
        } catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
        }
        return response($response);
    }

    /* Listing Of Co-Sharing*/
    public function co_sharing_listing()
    {
        $co_sharing_detail = session('co_sharing_detail');
        $records = [];
        if (!is_null($co_sharing_detail)) {
            $i = 1;
            foreach ($co_sharing_detail as $key => $row) {
                $button = '';
                $button .= '<button class="co_sharing_delete btn btn-sm btn-danger m-1" data-id="' . encryptid($row['id']) . '">
                   <i class="mdi mdi-delete"></i>
                   </button>';
                $records[] = array(
                    '0' => $i++,
                    '1' => $row['company_name'],
                    '2' => $row['type'],
                    '3' => $row['share'],
                    '4' => $row['policy_premium'],
                    '5' => $row['policy_terrorism_premium'],
                    '6' => $button
                );
            }
        }
        return response(['data' => $records]);
    }

    /* Delete Data of Co-Sharing */
    public function co_sharing_delete($id)
    {
        try {
            $co_sharing_detail = session('co_sharing_detail');
            $data = $co_sharing_detail[decryptid($id)];
            $data['flag'] = 2;
            unset($co_sharing_detail[decryptid($id)]);
            session()->put('co_sharing_detail', $co_sharing_detail);
            $response = [
                'status' => true,
                'data' => $data,
                'message' => 'Co-Sharing Detail Data Deleted Successfully',
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

    /* storing and updating sme-policy data */
    public function store(Request $request)
    {
        $request->validate(
            [
                'policy_type' => 'required',
                'product' => 'required',
                'irss_branch' => 'required',
                'policy_tenure' => 'required',
                'business_date' => 'required',
                'customer' => 'required',
                'agent' => 'required',
                'company' => 'required',
                'company_branch_name' => 'required',
                'branch_imd' => 'required',
                'sub_product' => 'required',
                'sum_insured' => 'required',
                'od' => 'required',
                'payment_type' => 'required',
                "policy_copy" => "sometimes|required|max:10000"
            ]
        );
        try {
            $policy_url = SmePolicy::find(decryptid($request->policy_id));
            $url = !empty($policy_url->policy_copy) ? $policy_url->policy_copy : null;
            $policy_copy_status = !empty($policy_url->policy_copy_status) ? $policy_url->policy_copy_status : 1;
            if ($request->has('policy_number') && $request->has('policy_copy')) {
                $company = Company::find($request->company);
                $url = 'policies/sme/' . str_replace(" ", "_", $company->name) . '_' . $company->id . '/' . str_replace('/', '', $request->policy_number) . '.pdf';
                if (Storage::disk('s3')->exists($url))
                    Storage::disk('s3')->delete($url);
                Storage::disk('s3')->put($url, file_get_contents($request->policy_copy));
                $policy_copy_status = 2;
            }
            $sme_policy = SmePolicy::updateOrCreate([
                'id' => decryptid($request->policy_id),
            ], [
                "policy_type" => $request->policy_type,
                "product_id" => $request->product,
                "business_date" => $request->business_date,
                "is_co_sharing" => isset($request->co_sharing) ? ($request->co_sharing == 'on' ? 2 : 1) : 1,
                "has_previous_policy" => isset($request->has_policy) ? ($request->has_policy == 'on' ? 2 : 1) : 1,
                "renewable_status" => $request->policyrenewstatus,
                'code_type' => $request->code_type,
                'edited_by_id'=>(decryptid($request->policy_id) != 0) ? Auth::user()->id : null,
                'edited_at'=>(decryptid($request->policy_id) != 0) ?  Carbon::now()->format('Y-m-d H:i:s'):null,
                "agent_id" => $request->agent,
                "previous_policy_number" => isset($request->has_policy) ? ($request->has_policy == 'on' ? $request->pre_policy_number : null) : null,
                "previous_start_date" => isset($request->has_policy) ? ($request->has_policy == 'on' ? $request->pre_start_date : null) : null,
                "previous_end_date" => isset($request->has_policy) ? ($request->has_policy == 'on' ? $request->pre_end_date : null) : null,
                "previous_company_id" => isset($request->has_policy) ? ($request->has_policy == 'on' ? $request->pre_company : null) : null,
                'renewal_previous_policy_number' => ($request->policy_type == 'renewal') ? (isset($request->renew_pre_policy_number) ? $request->renew_pre_policy_number : null) : null,
                "renewal_previous_company_id" => ($request->policy_type == 'renewal') ? (isset($request->previouspolicycompany) ? $request->previouspolicycompany : null) : null,
                "customer_id" => $request->customer,
                "irss_branch_id" => $request->irss_branch,
                "company_id" => $request->company,
                "company_branch_id" => $request->company_branch_name,
                "branch_imd_id" => $request->branch_imd,
                "sub_product_id" => $request->sub_product,
                "policy_number" => $request->policy_number,
                "policy_copy" => $url, // check policy upload or not
                "issue_date" => $request->issue_date,
                "policy_tenure" => $request->policy_tenure,
                "start_date" => $request->start_date,
                "end_date" => $request->end_date,
                "discount" => $request->discount,
                "sum_insured" => $request->sum_insured,
                "od" => $request->od,
                "stamp_duty" => $request->stamp_duty,
                "terrorism_premium" => $request->terrorism,
                "is_gst_value" => isset($request->chkgstvalue) ? 2 : 1,
                "gst" => $request->gst,
                "total_premium" => $request->total_premium,
                "occupancies" => $request->occupancies,
                "remark" => $request->remark,
                "co_sharing_policy_type" => isset($request->co_sharing_policy_type) ? $request->co_sharing_policy_type : null,
                "co_sharing_premium" => isset($request->policyprm) ? $request->policyprm : null,
                "co_sharing_terrorism_premium" => isset($request->policyterrorism) ? $request->policyterrorism : null,
                "co_sharing_share" => isset($request->share) ? $request->share : null,
                'policy_copy_status' => $policy_copy_status,
            ]);


            // inward number
            $settings = Settings::where('key', 'policy_no');
            $irss_branch = IrssBranch::where('id', $request->irss_branch)->with('city')->first(['id', 'city_id', 'policy_inward_code']);
            $inward_number = inwardFirstChar($request->policy_type) . str_pad($request->irss_branch, 2, '0', STR_PAD_LEFT) . str_replace('-', '', $irss_branch->city->rto_code) . Carbon::now()->format('y') . $irss_branch->policy_inward_code . $settings->first()->value;
            if (decryptid($request->policy_id) != 0) {
                SmePolicyPayment::where('policy_id', decryptid($request->policy_id))->delete();
            } else {
                $settings->update(['value' => str_pad(++$settings->first()->value, 6, '0', STR_PAD_LEFT)]);
                $sme_policy->update(['inward_no' => $inward_number,'created_by_id' => Auth::user()->id]);
            }
            //setup payment data by payment type
            $paymentData = get_payment_field($sme_policy->id, $request);
            SmePolicyPayment::insert($paymentData);

            // co-sharing data
            if (!is_null(session('co_sharing_detail'))) {
                foreach (session('co_sharing_detail') as $key => $data) {
                    $co_sharing_detail[] = array(
                        'policy_id' => $sme_policy->id,
                        'company_id' => $data['company_id'],
                        'co_sharing_premium' => $data['policy_premium'],
                        'co_sharing_terrorism_premium' => $data['policy_terrorism_premium'],
                        'co_sharing_share' => $data['share'],
                    );
                }
                CoSharingData::insert($co_sharing_detail);
            }

            $response = [
                'status' => true,
                'message' => 'SME Policy ' . $sme_policy->inward_no . (decryptid($request->policy_id) == 0 ? ' Created' : ' Updated') . ' Successfully.',
                'redirect_url' => 'sme-policy/create',
                'icon' => 'success',
                'redirect_url' => "sme-policy",
            ];
            $sme_policy = $sme_policy->toArray();
            $sme_policy['policy_type'] = 'SME';
            $sme_policy['notification_type'] = 'book';
            $sme_policy['message'] = (decryptid($request->policy_id) == 0 ? 'New ' : 'Updated ') . 'SME Policy(' . $sme_policy['inward_no'] . ') Recieved!';
            Agent::find($request->agent)->notify(new PolicyAddNotification($sme_policy));
        } catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'redirect_url' => 'sme-policy',
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
            $getPolicy = SmePolicy::with(['payments', 'customer', 'companyBranch', 'branch_imd_name', 'branch', 'product', 'sub_product', 'agent', 'created_by', 'updated_by','edited_by', 'previous_company', 'renewal_previous_company'])->where('id', $id)->first();
            $payment = $getPolicy->payments()->with('bank')->first();

            $od=(!empty($getPolicy->od))?$getPolicy->od :0;
            $add_on=0;
            $total_od=round($od + $add_on);
            $tp=(!empty($getPolicy->terrorism_premium))?$getPolicy->terrorism_premium :0;
            $pay_to_owner=0;
            $total_tp_or_ter=round($pay_to_owner + $tp);
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
                return view('pages.sme-policy.show', compact(['getPolicy', 'payment','data']));
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
            $data['products'] = Product::where('status', 1)->where('policy_type', 3)->get(['id', 'name']);
            $data['customers'] = Customer::where('status', 1)->get(['id', 'first_name', 'last_name', 'middle_name', 'prefix', 'customer_code']);
            $data['agents'] = Agent::where('status', 1)->get(['id', 'first_name', 'last_name', 'middle_name', 'prefix', 'code']);
            $data['irss_branches'] = IrssBranch::where('status', 1)->get(['id', 'name']);
            $data['companies'] = Company::where('status', 1)->get(['id', 'name']);
            $data['company_branchs'] = CompanyBranch::where('status', 1)->get(['id', 'name']);
            $data['branch_imd'] = BranchImdName::where('status', 1)->get(['id', 'name']);
            $data['sub_products'] = SubProduct::where('status', 1)->get(['id', 'name']);
            $data['policy'] = SmePolicy::find(decryptid($id));
            $data['payment_type'] = SmePolicyPayment::where('policy_id', decryptid($id))->first(['id', 'payment_type']);
            return view('pages.sme-policy.create', compact('data'));
        } catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
            return response($response);
        }
    }

    /* destroy data */
    public function destroy($id)
    {
        try {
            $update = ['status' => 2];
            $sme_policy = SmePolicy::where('id', decryptid($id));
            $sme_policy->update($update);
            $sme_policy = $sme_policy->first();
            $sme_policy->policy_type = 'SME';
            $sme_policy->notification_type = 'book';
            $sme_policy->message = 'Sme Policy(' . $sme_policy->inward_no . ') Deleted!';
            Agent::find($sme_policy['agent_id'])->notify(new PolicyAddNotification($sme_policy));
            $response = [
                'status' => true,
                'message' => "Health Policy " . $sme_policy->inward_no . "  Data Deleted Successfully",
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
            $data = SmePolicy::find(decryptid($id));
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
        $data['products'] = Product::where('status', 1)->where('policy_type', 2)->get(['id', 'name']);
        $data['agents'] = Agent::where('status', 1)->get(['id', 'first_name', 'last_name', 'middle_name', 'prefix', 'code']);
        return view('pages.update-all-policy.update_sme_policy', compact('data'));
    }

    public function update_policy_listing(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
        $totalRecords = SmePolicy::where('status', '!=', 2)->where('policy_number', null)->count();
        $sme_policies = smepolicy_data($request);
        $totalRecordswithFilter = $sme_policies->count();
        $sme_policies = $sme_policies
            ->skip($start)
            ->take($rowperpage)
            ->get(['id', 'product_id', 'customer_id', 'company_branch_id', 'policy_number', 'agent_id', 'inward_no', 'company_id', 'policy_tenure', 'branch_imd_id', 'issue_date', 'start_date', 'end_date', 'total_premium', 'od']);
        $records = [];
        $tenures = tenures();
        if (isset($sme_policies) && !empty($sme_policies)) {
            $sr = 1;
            foreach ($sme_policies as $key => $row) {

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
                    '0' => '<input type="checkbox" class="checkbox"  name="checkid[]" id="' . encryptid($row->id) . '"  onclick="single_unselected(this);" data-series="' . $sr . '"   style="    margin-left: 8px;"/><input type="hidden" name="id_' . $sr . '" value="' . encryptid($row->id) . '"><input type="hidden" name="policy_name_' . $sr . '" value="sme">',
                    '1' => '<select class="form-select form-control branch_imd "  name="branch_imd_' . $sr . '" >' . $html . ' </select>',
                    '2' => '- ' . $row->inward_no . '<br>- ' . ((!empty($row->company_id)) ? $row->company->name  : '' ). '<br>- ' . ((!empty($row->agent_id)) ? $row->agent->code : '' ),
                    '3' => !empty($row->product) ? $row->product->name : '',
                    '4' => '<p>' . $row->od . '</p>',
                    '5' => $row->total_premium,
                    '6' => '<div class="input-group ">
                    <input type="text" name="issue_date_' . $sr . '" value="' . $row->issue_date . '" class="form-control datepicker issue_date" autocomplete="off" id="issue_date"></div>',
                    '7' => '<select class="form-select form-control policy_tenure " id="policy_tenure_' . $sr . '" name="policy_tenure_' . $sr . '" onchange="addYears(' . $sr . ')">' . $policy_tenure_html . ' </select>',
                    '8' => '<div class="input-group ">
                    <input type="text" name="start_date_' . $sr . '" value="' . $row->start_date . '" class="form-control datepicker start_date" autocomplete="off" id="start_date_' . $sr . '" onchange="addYears(' . $sr . ')"></div>',
                    '9' => '<div class="input-group ">
                    <input type="text" name="end_date_' . $sr . '" value="' . $row->end_date . '" class="form-control" autocomplete="off" id="end_date_' . $sr . '" readonly></div>',
                    '10' => '<input type="text" class="form-control" name="policy_number_' . $sr . '" id="policy_number"  autocomplete="off"  value="' . $row->policy_number . '">',
                    '11' => '<input class="form-control" name="policy_copy_' . $sr . '" type="file" id="policy_copy" accept="application/pdf">',
                    '12' => (!empty($row->customer)) ? mb_strimwidth($row->customer->prefix . ' ' . $row->customer->first_name . ' ' . $row->customer->last_name, 0, 50, "..") : '',
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


    /*Display Payment Data of Health Policy*/
    public function get_payment_data(Request $request)
    {
        try {
            $data['payments'] = SmePolicyPayment::where('policy_id', decryptid($request->policy_id))->get()->toArray();

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

    public function export(Request $request)
    {
        $sme_policies = SmePolicy::where('status', '!=', 2)->with('product', 'customer', 'companyBranch', 'payments', 'agent', 'company');
        if ($request->ajax() && !empty($request->all())) {
            if (isset($request->data['agent']) && !empty($request->data['agent'])) {
                $sme_policies->where('agent_id', $request->data['agent']);
            }
            if (isset($request->data['name']) && !empty($request->data['name'])) {
                $sme_policies->whereHas('customer', function ($query) use ($request) {
                    $query->where('first_name', 'like', '%' . $request->data['name'] . '%')
                        ->orWhere('middle_name', 'like', '%' . $request->data['name'] . '%')
                        ->orWhere('last_name', 'like', '%' . $request->data['name'] . '%');
                });
            }
            if (isset($request->data['branch']) && !empty($request->data['branch'])) {
                $sme_policies->where('irss_branch_id', $request->data['branch']);
            }
            if (isset($request->data['company']) && !empty($request->data['company'])) {
                $sme_policies->whereHas('company', function ($queryData) use ($request) {
                    $queryData->where('id', $request->data['company']);
                });
            }
            if (isset($request->data['cheque_no']) && !empty($request->data['cheque_no'])) {
                $sme_policies->whereHas('payments', function ($query) use ($request) {
                    $query->where('payment_type', 2)->where('number', $request->data['cheque_no']);
                });
            }
            if (isset($request->data['inward_no']) && !empty($request->data['inward_no'])) {
                $sme_policies->where('inward_no', $request->data['inward_no']);
            }
            if (isset($request->data['policy_no']) && !empty($request->data['policy_no'])) {
                $sme_policies->where('policy_number', $request->data['policy_no']);
            }
            if (isset($request->data['product']) && !empty($request->data['product'])) {
                $sme_policies->whereHas('product', function ($query) use ($request) {
                    $query->where('id', $request->data['product']);
                });
            }
            if (isset($request->data['policy_start_date']) && !empty($request->data['policy_start_date']) && isset($request->data['policy_end_date']) && !empty($request->data['policy_end_date'])) {
                $sme_policies->where('start_date', '<=', $request->data['policy_start_date'])->where('end_date', '>=', $request->data['policy_end_date']);
            } else {
                if (isset($request->data['policy_start_date']) && !empty($request->data['policy_start_date'])) {
                    $sme_policies->where('start_date', $request->data['policy_start_date']);
                }
                if (isset($request->data['policy_end_date']) && !empty($request->data['policy_end_date'])) {
                    $sme_policies->where('end_date', $request->data['policy_end_date']);
                }
            }

            if (isset($request->data['from_date']) && !empty($request->data['from_date']) && isset($request->data['end_date']) && !empty($request->data['end_date'])) {

                $from_date = Carbon::parse($request->data['from_date'])->format('Y-m-d 00:00:00');
                $to_date = Carbon::parse($request->data['end_date'])->format('Y-m-d 23:59:59');
                $sme_policies->whereBetween('created_at', [$from_date, $to_date]);
            } else {
                if (isset($request->data['from_date']) && !empty($request->data['from_date'])) {
                    $from_date = Carbon::parse($request->data['from_date'])->format('Y-m-d 00:00:00');
                    $sme_policies->where('created_at', $from_date);
                }
                if (isset($request->data['end_date']) && !empty($request->data['end_date'])) {
                    $to_date = Carbon::parse($request->data['end_date'])->format('Y-m-d 23:59:59');
                    $sme_policies->where('created_at', $to_date);
                }
            }
        }
        $sme_policies = $sme_policies->latest()->get(['id', 'product_id', 'customer_id', 'company_branch_id', 'inward_no', 'policy_status', 'agent_id', 'company_id', 'policy_number', 'policy_copy_status', 'policy_copy']);

        if (!empty($sme_policies) && sizeof($sme_policies) != 0) {
            $myFile = Excel::raw(new ExportSmePolicy($request->data), 'Xlsx');
            $response = array(
                'status' => 'true',
                'name' => "SMEPolicy_data.xlsx",
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


    //Export update policy Data
    public function update_policy_export(Request $request)
    {
        $sme_policies = healthpolicy_data($request)->latest()->get(['id']);
        if (!empty($sme_policies) && sizeof($sme_policies) != 0) {
            $myFile = Excel::raw(new ExportUpdateSMEPolicy($request->data), 'Xlsx');
            $response = array(
                'status' => 'true',
                'name' => "PendingSMEPolicy_data.xlsx",
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
            $sme_policy = SmePolicy::where('id', decryptid($request->id));
            $sme_policy->update($update);
            $data['id'] = decryptid($request->id);
            $data['policy_type'] = 'SME';
            $data['notification_type'] = 'book';
            $data['message'] = 'Health Policy(' . $sme_policy->first()->inward_no . ') Cancelled!';
            Agent::find($sme_policy->first()->agent_id)->notify(new PolicyAddNotification($data));
            $response = [
                'status' => true,
                'message' => "SME Policy " . $sme_policy->first()->inward_no . " Cancelled Successfully",
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
    public function FodAgentListing(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
        $search_value=$request->search['value'];
        $totalRecords = SmePolicy::where('status', '!=', 2);
        isset(Auth::guard('agent')->user()->id) ?
            $totalRecords->where('agent_id', Auth::guard('agent')->user()->id) :
            $totalRecords->whereHas('agent', function ($query) use ($request) {
                $query->where('fdo_id', Auth::guard('fdo')->user()->id);
            });
        $sme_policies = SmePolicy::where('status', '!=', 2)->with('customer', 'agent', 'company', 'product');
        isset(Auth::guard('agent')->user()->id) ?
            $sme_policies->where('agent_id', Auth::guard('agent')->user()->id) :
            $sme_policies->whereHas('agent', function ($query) use ($request) {
                $query->where('fdo_id', Auth::guard('fdo')->user()->id);
            });
        isset(Auth::guard('agent')->user()->id) ?
            $sme_policies->where('agent_id', Auth::guard('agent')->user()->id) :
            $sme_policies->whereHas('agent', function ($query) use ($request) {
                $query->where('fdo_id', Auth::guard('fdo')->user()->id);
            });
        if (!is_null($search_value)) {
            $sme_policies->whereHas('customer', function ($query) use ($search_value) {
                $query->where('first_name', 'like', '%' . $search_value . '%')->orWhere('middle_name', 'like', '%' . $search_value . '%')->orWhere('last_name', 'like', '%' . $search_value . '%');
            });
        }
        $totalRecordswithFilter = $sme_policies->count();
        $sme_policies = $sme_policies
            ->skip($start)
            ->take($rowperpage)
            ->get(['id', 'product_id', 'customer_id', 'company_branch_id', 'inward_no', 'policy_status', 'agent_id', 'company_id', 'policy_number', 'policy_copy_status', 'policy_copy', 'status']);
        $records = [];
        if (isset($sme_policies) && !empty($sme_policies)) {
            foreach ($sme_policies as $key => $row) {
                $button = '';
                $button .= '<a href="' . route('fdo-agent.sme-policy.show', encryptid($row['id'])) . '"><button class="btn  btn-success m-1 view-button">view</button></a>';
                $button .= $row->policy_copy_status == 2 ? '<a href="' . route('policy.download', encryptid($row->policy_copy)) . '" class="policy_copy_download"><button class="btn btn-success m-1 download-button">Download</button></a>':'';
                $records[] = array(
                    '4' => "<p class='customer-name'>".$row->customer->first_name . ' ' . $row->customer->middle_name . ' ' . $row->customer->last_name."</p>",
                    '0' => "<span class='title'><b>Policy No.: </b></span>" . $row->policy_number,
                    '1' => !empty($row->product) ? "<span class='title'><b>Product Name: </b></span>".$row->product->name : '',
                    '2' => !empty($row->company) ? "<span class='title'><b>Company Name: </b></span>".getFirstString($row->company->name) : '',
                    '5' => "<span class='title'><b>Inward No.: </b></span>".$row->inward_no,
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
