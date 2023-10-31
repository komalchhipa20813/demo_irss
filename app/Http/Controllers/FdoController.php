<?php

namespace App\Http\Controllers;

use App\Models\{BusinessCategory, DocumentType, IrssBranch, User, Fdo, FdoDocuments, FdoServiceBranch, Settings};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Exports\ExportFdo;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class FdoController extends Controller {
    /* display fdo dashboard */
    public function index() {
        $data['business_category'] = BusinessCategory::where('status',1)->get();
        $data['home_branch'] = IrssBranch::where('status',1)->get();
        return view('pages.fdo.index',compact('data'));
    }

    /* listing of fdo */
    public function listing(Request $request) {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
        $totalRecords = Fdo::where('status','!=',2)->count();
        $fdos = Fdo::where('status', 1)->with('branch', 'documents');

        if($request->ajax()){
            if(isset($request->fdo_code)){
                   $fdos->where('code', $request->fdo_code);
            }

            if(isset($request->business_category)){
                $fdos->where('business_category_id',$request->business_category);
                    
            }
            if(isset($request->home_branch)){
                $fdos->where('home_irss_branch_id',$request->home_branch);
            }

            if(isset($request->pancard_no)){

                    $fdos->where('pancard_number', 'like', '%'.$request->pancard_no.'%');
            }
            if(isset($request->aadharcard_no)){
                   $fdos->where('adharcard_number', 'like', '%'.$request->aadharcard_no.'%');
            }
            if(isset($request->mobile_no)){
                $fdos->where('phone',$request->mobile_no)
                ->orWhere('secondary_phone',$request->mobile_no);
            }

            if (isset($request->start_date) && isset($request->end_date)) {
                $from_date = Carbon::parse($request->start_date)->format('Y-m-d 00:00:00');
                $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                $fdos->whereBetween('created_at', [$from_date, $to_date]);
            }else{
                if(isset($request->start_date)){
                    $from_date = Carbon::parse($request->start_date)->format('Y-m-d 00:00:00');
                    $fdos->where('created_at',$from_date);
                }
                if(isset($request->end_date)){
                    $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                    $fdos->where('created_at',$to_date);
                }
            }
        }
        $totalRecordswithFilter = $fdos->count();
        $fdos = $fdos
        ->skip($start)
        ->take($rowperpage)
        ->get();
        $records = [];
        $permissionList = permission();
        foreach ($fdos as $key => $row) {
            $button = '';

            if(in_array("129", $permissionList)){
                $button .= '<button class="btn btn-sm btn-success m-1 change-pwd"  data-id="'.encryptid($row['id']).'" >
                <i class="mdi mdi-account-key"></i>
                </button>';
            }
            if (in_array("75", $permissionList)) {
                $button .= '<a href="' . route('fdo.edit', encryptid($row['id'])) . '"><button class=" btn btn-sm btn-success m-1">
                <i class="mdi mdi-square-edit-outline"></i>
                </button></a>';
            }
            if (in_array("76", $permissionList)) {
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
                            <a href="' . asset('store/fdo/documents/' . $s_document->name) . '" download><i class="mdi mdi-download text-center"></i></a>
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
            $records[] = array(
                '0' => $key + 1,
                '1' => $row->code,
                '2' => $row->prefix . ' ' . $row->first_name . ' ' . $row->last_name,
                '3' => $row->email,
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
    /* checking fdo-email for availability */
    public function fdo_check(Request $request) {
        if (isset($request) && $request->email && $request->id) {
            $fdo = Fdo::where('email', $request->email)->where('id', '!=', decryptid($request->id))->where('status', '=', 1)->first('id');
            return (!is_null($fdo)) ? true : false;
        } else {
            return false;
        }
    }
    /* checking fdo-code for availability */
    public function fdo_code(Request $request) {
        if (isset($request) && $request->code && $request->id) {
            $fdo = Fdo::where('code', $request->code)->where('id', '!=', decryptid($request->id))->where('status', '=', 1)->first('id');
            return (!is_null($fdo)) ? true : false;
        } else {
            return false;
        }
    }

    /* checking Adhar Card Number for availability */
    public function adharcard_number(Request $request) {
        if (isset($request) && $request->adharcard_number && $request->id) {
            $fdo = Fdo::where('adharcard_number', $request->adharcard_number)->where('id', '!=', decryptid($request->id))->where('status', '=', 1)->first('id');
            return (!is_null($fdo)) ? true : false;
        } else {
            return false;
        }
    }

    /* checking Pan Card Number for availability */
    public function pancard_number(Request $request) {
        if (isset($request) && $request->pancard_number && $request->id) {
            $fdo = Fdo::where('pancard_number', $request->pancard_number)->where('id', '!=', decryptid($request->id))->where('status', '=', 1)->first('id');
            return (!is_null($fdo)) ? true : false;
        } else {
            return false;
        }
    }

    /* redirect to add fdo data page */
    public function create() {
        try {
            session()->forget('fdo_documents');
            $data['irss_branches'] = IrssBranch::where('status', 1)->get(['id', 'name']);
            $data['business_categories'] = BusinessCategory::where('status', 1)->get(['id', 'name']);
            $data['service_irss_branches'] = array();
            $data['fdo_documents'] = null;
            $data['document_types'] = DocumentType::where('status', 1)->get(['id', 'name']);
            $data['fdo'] = null;
            return view('pages.fdo.create', compact('data'));
        } catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
            return response($response);
        }
    }

    /* store or update fdo to database */
    public function store(Request $request) {
        try {
            $request->validate(
                [
                    'code' => 'required',
                    'branch' => 'required',
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
                    'adharcard_number' => 'required',
                    'pancard_number' => 'required',
                ]
            );
            if (isset($request->image)) {
                $image = time() . 'fdo_' . strtolower(substr($request->first_name, 0, 3)) . '.' . $request->image->extension();
                $request->image->move(public_path('store/fdo/profile/'), $image);
            }
            else {
            $image = decryptid($request->fdo_id) == 0?null:Fdo::find(decryptid($request->fdo_id))->image;
            }
            $fdo = Fdo::updateOrCreate(
                [
                    'id' => decryptid($request->fdo_id),
                ],
                [
                    'image' => $image,
                    'code' => $request->code,
                    'home_irss_branch_id' => $request->branch,
                    'email' => (isset($request->email)) ? $request->email : null,
                    'secondary_email' => (isset($request->secondary_email)) ? $request->secondary_email : null,
                    'business_category_id' => $request->business_category,
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
                    'bank_name' =>(isset($request->bank_name))? $request->bank_name:null,
                    'bank_branch_name' =>(isset($request->bank_branch_name))? $request->bank_branch_name:null,
                    'account_number' => (isset($request->salary_account)) ? $request->salary_account : null,
                    'ifsc_code' => (isset($request->ifsc_code)) ? $request->ifsc_code : null,
                    'holder_name' => (isset($request->account_holder)) ? $request->account_holder : null,
                    'adharcard_number' => $request->adharcard_number,
                    'pancard_number' => $request->pancard_number,
                ]

            );
            if(decryptid($request->fdo_id) == 0){
                $fdo->update(['password'=>Hash::make('123456')]);
            }
            $fdo_id = (decryptid($request->fdo_id) == 0) ? $fdo->id : decryptid($request->fdo_id);
            FdoServiceBranch::where('fdo_id', decryptid($request->fdo_id))->delete();
            $irss_branches = (!in_array("0", $request->service_branch)) ? $request->service_branch : IrssBranch::where('status', 1)->pluck('id');
            foreach ($irss_branches as $key => $branch) {
                $fdo_service[] = array(
                    'fdo_id' => $fdo_id,
                    'irss_branch_id' => $branch,
                );
            }
            if (!is_null(session('fdo_documents'))) {
                foreach (session('fdo_documents') as $key => $document) {
                    $fdo_documents[] = array(
                        'fdo_id' => $fdo_id,
                        'document_type' => $document['document_type'],
                        'name' => $document['document_file'],
                        'number' => $document['document_number'],
                    );
                }
                FdoDocuments::insert($fdo_documents);
            }
            FdoServiceBranch::insert($fdo_service);
            $response = [
                'status' => true,
                'message' => 'FDO ' . $request->code.(decryptid($request->fdo_id) == '0' ? ' Created' : ' Updated') . ' Successfully',
                'icon' => 'success',
                'redirect_url' => "fdo",
            ];
        } catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
                'redirect_url' => "fdo",
            ];
        }
        return response($response);
    }

    public function show($id) {
        try {
            $fdo = Fdo::with('branch', 'business_category')->find(decryptid($id));
            return view('pages.fdo.show', compact('fdo'));
        } catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
            return response($response);
        }
    }

    public function edit($id) {
        try {
            session()->forget('fdo_documents');
            $data['irss_branches'] = IrssBranch::where('status', 1)->get(['id', 'name']);
            $data['business_categories'] = BusinessCategory::where('status', 1)->get(['id', 'name']);
            $data['service_irss_branches'] = FdoServiceBranch::where('fdo_id', decryptid($id))->pluck('irss_branch_id')->toArray();
            $data['document_types'] = DocumentType::where('status', 1)->get(['id', 'name']);
            $data['fdo_documents'] = FdoDocuments::where('fdo_id', decryptid($id))->where('status', 1)->pluck('document_type')->toArray();
            $data['fdo'] = Fdo::find(decryptid($id));
            return view('pages.fdo.create', compact('data'));
        } catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
            return response($response);
        }
    }

    public function destroy($id) {
        try {
            $update = ['status' => 2];
            $fdo=Fdo::where('id', decryptid($id))->first();
            $fdo->update($update);
            $response = [
                'status' => true,
                'message' => "FDO '.$fdo->code.' Data Deleted Successfully",
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
    /* store or update fdo documents */
    public function fdo_document(Request $request) {
        try {
            $request->validate(
                [
                    'document_file' => 'required',
                    'document_number' => 'required',
                ]
            );
            if (isset($request->document_file)) {
                $document_name = time() . 'fdo.' . $request->document_file->extension();
                $request->document_file->move(public_path('store/fdo/documents/'), $document_name);
            }
            if(empty(session('fdo_documents'))){
                $id=1;
            }else{
                foreach (session('fdo_documents') as $key => $value) {
                    $i = $value['id'];
                }
                $id = $i + 1;
            }
            $document = session()->get('fdo_documents', []);
            $document[$id] = [
                "id" => $id,
                'document_type' => $request->document_type,
                'document_type_name' => $request->document_name,
                "document_file" => $document_name,
                "document_number" => $request->document_number,
            ];
            session()->put('fdo_documents', $document);
            $response = [
                'status' => true,
                'message' => 'FDO Document Created Successfully',
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
    /* listing of fdo documents */
    public function document_listing() {
        $fdo_documents = session('fdo_documents');
        $records = [];
        if (!is_null($fdo_documents)) {
            foreach ($fdo_documents as $key => $row) {
                $button = '';
                if (in_array("48", permission())) {
                    $button .= '<button class="document_delete btn btn-sm btn-danger m-1" data-id="' . encryptid($row['id']) . '">
                    <i class="mdi mdi-delete"></i>
                    </button>';
                }
                $document_file = '<a href="' . asset('store/fdo/documents/' . $row['document_file']) . '" download>Download File</a>';
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
    /* delete fdo documents */
    public function fdo_delete($id) {
        try {
            $fdo_documents = session('fdo_documents');
            unset($fdo_documents[decryptid($id)]);
            session()->put('fdo_documents', $fdo_documents);
            $response = [
                'status' => true,
                'message' => 'FDO Document Deleted Successfully',
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
    public function login_create() {
        return view('pages.fdo-agent-panel.login');
    }
    public function login_store(Request $request) {
        $check = $request->all();
        $guard_name = $request->login_type == 1 ? 'fdo' : 'agent';
        if (Auth::guard($guard_name)->attempt(['code' => $check['code'], 'password' => $check['password']])) {
            return redirect(route('fdo.agent.dashboard'));
        } else {
            $error = 'Please enter valid email and password!';
            return (View('pages.fdo-agent-panel.login', compact('error')));
        }
    }
    public function fdo_agent_destroy(Request $request) {
        $guard_name = isset(Auth::guard('fdo')->user()->id) ? 'fdo' : 'agent';
        Auth::guard($guard_name)->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route('fdo.agent.login'));
    }

    public function export() 
    {
        return Excel::download(new ExportFdo, 'fdos.xlsx');
    }
    /* get service branch of fdo */
    public function get_data(Request $request) {
        try{
            $branch_id=FdoServiceBranch::where('fdo_id',$request->fdo_id)->pluck('irss_branch_id');
            $response = [
                'status' => true,
                'branch' => IrssBranch::whereIn('id',$branch_id)->get(['id','name'])
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
    public function notification() {
        Auth::guard('agent')->user()->unreadNotifications()->update(['read_at' => now()]);
        return view('pages.fdo-agent-panel.notification');
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

            $user=Fdo::where('id',decryptid($request->id))->first();
            if(!empty($user))
            {
                $user->update(['password' => Hash::make($request->password)]);

                $response = [
                    'status' => true,
                    'message' => 'Password Change Successfully',
                    'icon' => 'success',
                    'redirect_url' => "fdo",
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
