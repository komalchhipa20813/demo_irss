<?php

namespace App\Http\Controllers;

use App\Models\{BusinessCategory, DocumentType, Agent, AgentDocuments, Fdo, IrssBranch, Settings, User};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Exports\AgentsExport;
use App\Notifications\PolicyAddNotification;
use Maatwebsite\Excel\Facades\Excel;

class AgentController extends Controller
{

    //dashboard of agent
    public function index()
    {
        $data['business_category'] = BusinessCategory::where('status', 1)->get();
        $data['sales_manager'] = User::where('id', '!=', 1)->where('status', 1)->get(['id', 'first_name', 'middle_name', 'last_name']);
        $data['home_branch'] = IrssBranch::where('status', 1)->get();
        return view('pages.agent.index', compact('data'));
    }

    // redirect to create page
    public function create()
    {
        try {
            session()->forget('agent_documents');
            $data['business_categories'] = BusinessCategory::where('status', 1)->get(['id', 'name']);
            $data['agent_documents'] = null;
            $data['document_types'] = DocumentType::where('status', 1)->get(['id', 'name']);
            $data['fdos'] = Fdo::where('status', 1)->get(['id', 'code', 'first_name', 'last_name', 'middle_name', 'prefix']);
            $data['agent'] = null;
            return view('pages.agent.create', compact('data'));
        } catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
            return response($response);
        }
    }

    /* checking agent-email for availability */
    public function agent_check(Request $request)
    {
        if (isset($request) && $request->email && $request->id) {
            $agent = Agent::where('email', $request->email)->where('id', '!=', decryptid($request->id))->where('status', '=', 1)->first('id');
            return (!is_null($agent)) ? true : false;
        } else {
            return false;
        }
    }

    /* store or update agent to database */
    public function store(Request $request)
    {
        try {
            $request->validate(
                [
                    'branch' => 'required',
                    'fdo' => 'required',
                    'prefix' => 'required',
                    'first_name' => 'required',
                    'middle_name' => 'required',
                    'last_name' => 'required',
                    'phone' => 'required',
                    'office_address' => 'required',
                    'residential_address' => 'required',
                    'gender' => 'required',
                    'dob' => 'required',
                    'joining_date' => 'required',
                    'sales_manager' => 'required',
                ]
            );
            if (isset($request->image)) {
                $image = time() . 'agent_' . strtolower(substr($request->first_name, 0, 3)) . '.' . $request->image->extension();
                $request->image->move(public_path('store/agent/profile/'), $image);
            } else {
                $image = (decryptid($request->agent_id) == 0) ? null : Agent::find(decryptid($request->agent_id))->image;
            }
            $agent = Agent::updateOrCreate(
                [
                    'id' => decryptid($request->agent_id),
                ],
                [
                    'image' => $image,
                    'fdo_id' => $request->fdo,
                    'home_irss_branch_id' => $request->branch,
                    'email' => (isset($request->email)) ? $request->email : null,
                    'secondary_email' => (isset($request->secondary_email)) ? $request->secondary_email : null,
                    'prefix' => $request->prefix,
                    'first_name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'phone' => $request->phone,
                    'secondary_phone' => (isset($request->secondary_phone)) ? $request->secondary_phone : null,
                    'office_address' => $request->office_address,
                    'residential_address' => $request->residential_address,
                    'gender' => $request->gender,
                    'dob' => $request->dob,
                    'joining_date' => $request->joining_date,
                    'anniversary_date' => (isset($request->anniversary_date)) ? $request->anniversary_date : null,
                    'salary' => (isset($request->salary)) ? $request->salary : null,
                    'bank_name' => (isset($request->bank_name)) ? $request->bank_name : null,
                    'bank_branch_name' => (isset($request->bank_branch_name)) ? $request->bank_branch_name : null,
                    'account_number' => (isset($request->salary_account)) ? $request->salary_account : null,
                    'ifsc_code' => (isset($request->ifsc_code)) ? $request->ifsc_code : null,
                    'holder_name' => (isset($request->account_holder)) ? $request->account_holder : null,
                    'sales_manager_id' => $request->sales_manager,
                    'adharcard_number' => $request->adharcard_number,
                    'pancard_number' => $request->pancard_number,
                ]
            );
            $fdo_code = Fdo::find($request->fdo)->code;
            if (decryptid($request->agent_id) == 0) {
                $settingData = Settings::where('key', $fdo_code)->first();
                if (empty($settingData)) {
                    Settings::insert(['key' => $fdo_code, 'value' => '01AA']);
                    $settingData = Settings::where('key', $fdo_code)->first();
                }
                $agent->update([
                    'code' => $fdo_code . $settingData->value,
                    'password' => Hash::make('123456')
                ]);
                $settingData->update(['value' => generateAgentCode($settingData->value)]);
            }
            $agent_id = (decryptid($request->agent_id) == 0) ? $agent->id : decryptid($request->agent_id);
            if (!is_null(session('agent_documents'))) {
                foreach (session('agent_documents') as $key => $document) {
                    $agent_documents[] = array(
                        'agent_id' => $agent_id,
                        'document_type' => $document['document_type'],
                        'name' => $document['document_file'],
                        'number' => $document['document_number'],
                    );
                }
                AgentDocuments::insert($agent_documents);
            }
            $agent = $agent->toArray();
            $agent['policy_type'] = null;
            $agent['notification_type'] = 'user';
            $agent['message'] = (decryptid($request->agent_id) == 0 ? 'New ' : 'Updated ') . 'Agent(' . $agent['code'] . ') Recieved!';
            Fdo::find($request->fdo)->notify(new PolicyAddNotification($agent));
            $response = [
                'status' => true,
                'message' => 'Agent ' . $agent['code'] . (decryptid($request->agent_id) == '0' ? ' Created' : ' Updated') . ' Successfully',
                'icon' => 'success',
                'redirect_url' => "agent",
            ];
        } catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
                'redirect_url' => "agent",
            ];
        }
        return response($response);
    }

    public function edit($id)
    {
        try {
            session()->forget('agent_documents');
            $data['business_categories'] = BusinessCategory::where('status', 1)->get(['id', 'name']);
            $data['document_types'] = DocumentType::where('status', 1)->get(['id', 'name']);
            $data['agent_documents'] = AgentDocuments::where('agent_id', decryptid($id))->where('status', 1)->pluck('document_type')->toArray();
            $data['fdos'] = Fdo::where('status', 1)->get(['id', 'first_name', 'last_name', 'middle_name', 'prefix', 'code']);
            $data['agent'] = Agent::find(decryptid($id));
            return view('pages.agent.create', compact('data'));
        } catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
            return response($response);
        }
    }

    public function destroy($id)
    {
        try {
            $update = ['status' => 2];
            $agent = Agent::where('id', decryptid($id))->first();
            $agent->update($update);
            $agent = $agent->toArray();
            $agent['policy_type'] = null;
            $agent['notification_type'] = 'user';
            $agent['message'] = 'Agent(' . $agent['code'] . ') Deleted!';
            Fdo::find($agent->fdo_id)->notify(new PolicyAddNotification($agent));
            $response = [
                'status' => true,
                'message' => "Agent " . $agent['code'] . " Data Deleted Successfully",
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
    /* listing of agent */
    public function listing(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
        $totalRecords = Agent::where('status', '!=', 2)->count();
        $agents = Agent::where('status', 1)->with('fdo', 'branch', 'documents');
        if (!is_null(Auth::guard('fdo')->user()))
            $agents->where('fdo_id', Auth::guard('fdo')->user()->id);
        if ($request->ajax()) {
            if (isset($request->fdo_code)) {
                $agents->whereHas('fdo', function ($query) use ($request) {
                    $query->where('code', 'like', '%' . $request->fdo_code . '%');
                });
            }
            if (isset($request->agent_code)) {
                $agents->where('code', $request->agent_code);
            }
            if (isset($request->agent_name)) {
                $agents->where('first_name', 'like', '%' . $request->agent_name . '%')
                    ->orWhere('middle_name', 'like', '%' . $request->agent_name . '%')
                    ->orWhere('last_name', 'like', '%' . $request->agent_name . '%');
            }

            if (isset($request->business_category)) {
                $agents->whereHas('fdo', function ($queryData) use ($request) {
                    $queryData->whereHas('business_category', function ($subQuery) use ($request) {
                        $subQuery->where('id', $request->business_category);
                    });
                });
            }
            if (isset($request->home_branch)) {
                $agents->where('home_irss_branch_id', $request->home_branch);
            }
            if (isset($request->sales_manager)) {
                $agents->where('sales_manager_id', $request->sales_manager);
            }

            if (isset($request->pancard_no)) {
                $agents->whereHas('documents', function ($query) use ($request) {
                    $query->where('document_type', 1)->where('number', 'like', '%' . $request->pancard_no . '%');
                });
            }
            if (isset($request->aadharcard_no)) {
                $agents->whereHas('documents', function ($query) use ($request) {
                    $query->where('document_type', 1)->where('number', 'like', '%' . $request->aadharcard_no . '%');
                });
            }
            if (isset($request->mobile_no)) {
                $agents->where('phone', $request->mobile_no)
                    ->orWhere('secondary_phone', $request->mobile_no);
            }

            if (isset($request->start_date) && isset($request->end_date)) {
                $from_date = Carbon::parse($request->start_date)->format('Y-m-d 00:00:00');
                $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                $agents->whereBetween('created_at', [$from_date, $to_date]);
            } else {
                if (isset($request->start_date)) {
                    $from_date = Carbon::parse($request->start_date)->format('Y-m-d 00:00:00');
                    $agents->where('created_at', $from_date);
                }
                if (isset($request->end_date)) {
                    $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                    $agents->where('created_at', $to_date);
                }
            }
        }

        $totalRecordswithFilter = $agents->count();
        $agents = $agents
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $records = [];
        $permissionList = isset(Auth::user()->id) ? permission() : '';
        $fdoName = "";
        foreach ($agents as $key => $row) {

            $button = '';
            if(isset(Auth::user()->id) && in_array("129", $permissionList)){
                $button .= '<button class="btn btn-sm btn-success m-1 change-pwd"  data-id="'.encryptid($row['id']).'" >
                <i class="mdi mdi-account-key"></i>
                </button>';
            }
            if (isset(Auth::user()->id) && in_array("79", $permissionList)) {
                $button .= '<a href="' . route('agent.edit', encryptid($row['id'])) . '"><button class=" btn btn-sm btn-success m-1">
                <i class="mdi mdi-square-edit-outline"></i>
                </button></a>';
            }
            if (isset(Auth::user()->id) && in_array("80", $permissionList)) {
                $button .= '<button class="delete btn btn-sm btn-danger m-1" data-id="' . encryptid($row['id']) . '">
                <i class="mdi mdi-delete"></i>
                </button>';
            }
            $documents = '<button class="btn btn-sm btn-success m-1" data-bs-toggle="modal" data-bs-target="#m-' . $row->id . '">
            <i class="mdi mdi-eye"></i>
            </button>
            <div class="modal fade" id="m-' . $row->id . '" tabindex="-1" aria-labelledby="' . $row->id . 'Label" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="' . $row->id . 'Label">Modal title</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>
                      Document Name
                    </th>
                    <th>
                      Download
                    </th>
                  </tr>
                </thead>
                <tbody>';
            foreach ($row->documents as $s_document) {
                $documents .= '<tr>
                        <td>
                            ' . $s_document->documents_type->name . '
                        </td>
                        <td>
                            <a href="' . asset('store/agent/documents/' . $s_document->name) . '" download><i class="mdi mdi-download text-center"></i></a>
                        </td>
                    </tr>';
            }
            $documents .= '</tbody>
              </table>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>';
            if (isset($row->fdo) && $row->fdo != "") {
                $fdoName =  $row->fdo->first_name . ' ' . $row->fdo->middle_name . ' ' . $row->fdo->last_name;
            }

            $records[] = array(
                '0' => $key + 1,
                '1' => $row->code,
                '2' => $row->prefix . ' ' . $row->first_name . ' ' . $row->last_name,
                '3' => $fdoName,
                '4' => (isset($row->branch) && $row->branch != "" ? $row->branch->name : ''),
                '5' => $documents,
                '6' => $button
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
    /* listing of agent documents */
    public function document_listing()
    {
        $agent_documents = session('agent_documents');
        $records = [];
        if (!is_null($agent_documents)) {
            foreach ($agent_documents as $key => $row) {
                $button = '';
                if (in_array("48", permission())) {
                    $button .= '<button class="document_delete btn btn-sm btn-danger m-1" data-id="' . encryptid($row['id']) . '">
                    <i class="mdi mdi-delete"></i>
                    </button>';
                }
                $document_file = '<a href="' . asset('store/agent/documents/' . $row['document_file']) . '" download>Download File</a>';
                $records[] = array(
                    '0' => $key,
                    '1' => $row['document_type_name'],
                    '2' => $document_file,
                    '3' => $row['document_number'],
                    '4' => $button
                );
            }
        }
        return response(['data' => $records]);
    }
    /* store or update agent documents */
    public function agent_document(Request $request)
    {
        try {
            $request->validate(
                [
                    'document_file' => 'required',
                    'document_number' => 'required',
                ]
            );
            if (isset($request->document_file)) {
                $document_name = time() . 'agent.' . $request->document_file->extension();
                $request->document_file->move(public_path('store/agent/documents/'), $document_name);
            }
            if (empty(session('agent_documents'))) {
                $id = 1;
            } else {
                foreach (session('agent_documents') as $key => $value) {
                    $i = $value['id'];
                }
                $id = $i + 1;
            }
            $document = session()->get('agent_documents', []);
            $document[$id] = [
                "id" => $id,
                'document_type' => $request->document_type,
                'document_type_name' => $request->document_name,
                "document_file" => $document_name,
                "document_number" => $request->document_number,
            ];
            session()->put('agent_documents', $document);
            $response = [
                'status' => true,
                'message' => 'Agent Document Created Successfully',
                'icon' => 'success',
                'document_type' => $request->document_type
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
    /* delete agent documents */
    public function agent_delete($id)
    {
        try {
            $agent_documents = session('agent_documents');
            unset($agent_documents[decryptid($id)]);
            session()->put('agent_documents', $agent_documents);
            $response = [
                'status' => true,
                'message' => 'Agent Document Deleted Successfully',
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

    public function export()
    {
        return Excel::download(new AgentsExport, 'agents.xlsx');
    }
    /* checking Adhar Card Number for availability */
    public function adharcard_number(Request $request)
    {
        if (isset($request) && $request->adharcard_number && $request->id) {
            $agent = Agent::where('adharcard_number', $request->adharcard_number)->where('id', '!=', decryptid($request->id))->where('status', '=', 1)->first('id');
            return (!is_null($agent)) ? true : false;
        } else {
            return false;
        }
    }

    /* checking Pan Card Number for availability */
    public function pancard_number(Request $request)
    {
        if (isset($request) && $request->pancard_number && $request->id) {
            $agent = Agent::where('pancard_number', $request->pancard_number)->where('id', '!=', decryptid($request->id))->where('status', '=', 1)->first('id');
            return (!is_null($agent)) ? true : false;
        } else {
            return false;
        }
    }

    public function changePassword(Request $request)
    {
        try{
            $request->validate(
                [
                    'password' => 'required',
			        'confirmpassword'  => 'required' . '|same:password',
                ]
            );

            $user=Agent::where('id',decryptid($request->id))->first();
            if(!empty($user))
            {
                $user->update(['password' => Hash::make($request->password)]);

                $response = [
                    'status' => true,
                    'message' => 'Password Change Successfully',
                    'icon' => 'success',
                    'redirect_url' => "agent",
                ];
            }
        }catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' =>'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
                'redirect_url' => "employee",
            ];
        }
        return response($response);

    }
}
