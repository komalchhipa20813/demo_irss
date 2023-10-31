<?php

use App\Models\HealthPolicy;
use App\Models\MotorPolicy;
use App\Models\Role_Permission;
use App\Models\{Settings};
use App\Models\SmePolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

if (!function_exists('permissions')) {

	function permission() {
		$role_id = Auth::user()->role_id;
		$permissions = Role_Permission::select('permission_id')->where('role_id', $role_id)->pluck('permission_id')->toArray();
        return($permissions);
	}
}
// / encrypt primary key of user /
if (!function_exists('encryptid')) {
	function encryptid($string) {
		$encrypted = Crypt::encryptString($string);
		return $encrypted;
	}
}

// / decrypt primary key of user /
if (!function_exists('decryptid')) {
	function decryptid($string) {
		$decrypted = Crypt::decryptString($string);
		return $decrypted;
	}
}

    function active_class($path, $active = 'active') {
      return call_user_func_array('Request::is', (array)$path) ? $active : '';
    }

    function is_active_route($path) {
      return call_user_func_array('Request::is', (array)$path) ? 'true' : 'false';
    }

    function show_class($path) {
      return call_user_func_array('Request::is', (array)$path) ? 'show' : '';
    }
	function array_setting(){
		$path=['retinue-branch','department','designation','document-type','business-category','company','company-branch','branch-imd','product','product/*','sub-product','country','state','city','products/*','make-product','product-model','product-variant','bank','product-type','leave-application','leave-application/*','sales-manager'];
		return $path;
	}
	function array_general(){
		$path=['retinue-branch','department','designation','document-type','business-category','bank','sales-manager'];
		return $path;
	}
	function array_company(){
		$path=['company','company-branch','branch-imd'];
		return $path;
	}
    function array_products(){
        $path=['product','sub-product','product/*','product-type'];
        return $path;
    }
    function array_address(){
        $path=['country','state','city'];
        return $path;
    }

	function array_motor_policy(){
		$path=['make-product','product-model','product-variant'];
		return $path;
	}

    function array_raise_query()
    {
        $path=['raise-query','raise-query/*'];
		return $path;
    }

	function array_leave_application(){
		$path=['leave-application','leave-application/*'];
		return $path;
	}

	function array_IRSS_master(){
		$path=['customer','customer/create','fdo-agent/customer','fdo','fdo/*','agent','agent/*','fdo-agent/agent'];
		return $path;
	}
    function agent_master(){
        $path=['agent','agent/*'];
        return $path;
    }
    function fdo_master(){
        $path=['fdo','fdo/*'];
        return $path;
    }
    function array_policy(){
		$path=['health-policy','health-policy/*','motor-policy','motor-policy/*','sme-policy','sme-policy/*'];
		return $path;
	}
    function array_policy_fdo_master(){
		$path=['fdo-agent/health-policy','fdo-agent/motor-policy','fdo-agent/sme-policy'];
		return $path;
	}
    function motor_policy_inward()
    {
        $path=['motor-policy','motor-policy/*'];
        return $path;
    }
    function sme_policy_inward()
    {
        $path=['sme-policy','sme-policy/*'];
        return $path;
    }
    function helth_policy_inward()
    {
        $path=['health-policy','health-policy/*'];
        return $path;
    }
     function array_updatepolicy(){
        $path=['update-policy/*'];
        return $path;
    }

    function array_reports(){
        $path=['not-uploaded-policy','not-uploaded-policy/*','agent-details','fdo-details','generate-outward','generate-outward/*','generated-outward/*','generated-outward','gross-business','cancel-policy/motor','cancel-policy/health','cancel-policy/sme'];
        return $path;
    }

    function array_notUpload_pdf_reports(){
        $path=['not-uploaded-policy/motor','not-uploaded-policy/health','not-uploaded-policy/sme'];
        return $path;
    }
    function array_cancel_policy_reports(){
        $path=['cancel-policy/motor','cancel-policy/health','cancel-policy/sme'];
        return $path;
    }
    if (!function_exists('dashboard_calculation')) {
        function dashboard_calculation($table){
            return DB::table($table)->select('id')->where('status',1)->count();
        }
    }
	if (!function_exists('payment_type')) {
        function payment_type(){
			return  array(
				array('name'=>'Cash','key'=>'1'),
				array('name'=>'Cheque','key'=>'2'),
				array('name'=>'Demand Draft','key'=>'3'),
				array('name'=>'Online Payment','key'=>'4'),
				array('name'=>'Cash/Cheque','key'=>'5'),
				array('name'=>'Cheque/Demand Draft','key'=>'6'),
				array('name'=>'Cash/Demand Draft','key'=>'7'),
				array('name'=>'Cash/Online Payment','key'=>'8'),
				array('name'=>'Online Payment/Cheque','key'=>'9'),
				array('name'=>'Online Payment/Demand Draft','key'=>'10'),
			);

		}
    }
    if (!function_exists('get_payment_type')) {
        function get_payment_type($payment){
            $payment_type='';
                if($payment == 1)
                {
                    $payment_type='CASH';
                }
                elseif ($payment == 2) {
                    $payment_type='CHEQUE';
                }
                elseif ($payment == 3) {
                    $payment_type='DEMAND DRAFT';
                }
                elseif ($payment == 4) {
                    $payment_type='ONLINE PAYMENT';
                }
                elseif ($payment == 5) {
                    $payment_type='CASH/CHEQUE';
                }
                elseif ($payment == 6) {
                    $payment_type='CHEQUE/DEMAND DRAFT';
                }
                elseif ($payment == 7) {
                    $payment_type='CASH/DEMAND DRAFT';
                }
                elseif ($payment == 8) {
                    $payment_type='CASH/ONLINE PAYMENT';
                }
                elseif ($payment == 9) {
                    $payment_type='ONLINE PAYMENT/CHEQUE';
                }
                elseif ($payment == 10) {
                    $payment_type='ONLINE PAYMENT/DEMAND DRAFT';
                }
                return $payment_type;

        }
    }

    if (!function_exists('date_format_date')) {

    function date_format_date($date) {
        try{
            $formate='';
            if(!empty($date))
            {
                $formate= Carbon::parse($date)->format('d/m/Y');
            }
            return($formate);
        }catch(\Throwable $e){
            return($date);
        }
    }
    }

    if(!function_exists('get_payment_field')){
        function get_payment_field($policy_id,$requestData){
            $type= $requestData->payment_type;
            switch ($type) {
                case "1":
                    return [
                        //Cash
                        'payment_type'=>$type,
                        'policy_id'=> $policy_id,
                        'bank_id' => null,
                        'amount' => (isset($requestData) && isset($requestData->cash_amount) ? $requestData->cash_amount : 0),
                        'account_number' => null,
                        'number' => null,
                        'payment_date' => null,
                    ];
                    break;
                case "2":
                    $chequeData[]=[
                        //Cheque
                        'payment_type'=>$type,
                        'policy_id'=> $policy_id,
                        'bank_id' => $requestData->cheque_bank,
                        'amount' => $requestData->cheque_amount,
                        'account_number' => $requestData->cheque_account_number,
                        'number' => $requestData->cheque_number,
                        'payment_date' => $requestData->cheque_date,

                    ];
                    if(isset($requestData) && isset($requestData->cheque_bank_1)){
                        $chequeData[]=[
                            //Second Cheque
                            'payment_type'=>$type,
                            'policy_id'=> $policy_id,
                            'bank_id' => (isset($requestData->cheque_bank_1) ? $requestData->cheque_bank_1 : null),
                            'amount' => (isset($requestData->cheque_amount_1) ? $requestData->cheque_amount_1 : null),
                            'account_number' => (isset($requestData->cheque_account_number_1) ? $requestData->cheque_account_number_1 : null),
                            'number' => (isset($requestData->cheque_number_1) ? $requestData->cheque_number_1 : null),
                            'payment_date' => (isset($requestData->cheque_date_1) ? $requestData->cheque_date_1 : null),
                        ];
                    }
                    return $chequeData;
                    break;
                case "3":
                    $demandDraft[]=[
                        // Demand Draft
                        'payment_type'=>$type,
                        'policy_id'=> $policy_id,
                        'bank_id' => $requestData->dd_bank,
                        'amount' => $requestData->dd_amount,
                        'account_number' => $requestData->dd_account_number,
                        'number' => $requestData->dd_number,
                        'payment_date' => $requestData->dd_date,
                    ];
                    if(isset($requestData) && isset($requestData->dd_bank_1)){
                        $demandDraft[]=[
                            //Second Cheque
                            'payment_type'=>$type,
                            'policy_id'=> $policy_id,
                            'bank_id' => $requestData->dd_bank_1,
                            'amount' => $requestData->dd_amount_1,
                            'account_number' => $requestData->dd_account_number_1,
                            'number' => $requestData->dd_number_1,
                            'payment_date' => $requestData->dd_date_1,
                        ];
                    }
                    return $demandDraft;
                    break;
                case "4":
                    $onlineTransaction[] =  [
                        //Online Transaction
                        'payment_type'=>$type,
                        'policy_id'=> $policy_id,
                        'bank_id' => $requestData->transaction_bank,
                        'amount' => $requestData->online_amount,
                        'account_number' => null,
                        'number' => $requestData->transaction_number,
                        'payment_date' => $requestData->transaction_date,
                    ];
                    if(isset($requestData) && isset($requestData->transaction_bank_1)){
                        $onlineTransaction[]=[
                            //Second Cheque
                            'payment_type'=>$type,
                            'policy_id'=> $policy_id,
                            'bank_id' => $requestData->transaction_bank,
                            'amount' => $requestData->online_amount,
                            'account_number' => null,
                            'number' => $requestData->transaction_number,
                            'payment_date' => $requestData->transaction_date,

                        ];
                    }
                    return $onlineTransaction;
                    break;
                case "5":
                    // Cash & Cheque
                    return [[
                        'payment_type'=>$type,
                        'policy_id'=> $policy_id,
                        'bank_id' => null,
                        'amount' => (isset($requestData) && isset($requestData->cash_amount) ? $requestData->cash_amount : 0),
                        'account_number' => null,
                        'number' => null,
                        'payment_date' => null,
                    ],[
                        'payment_type'=>$type,
                        'policy_id'=> $policy_id,
                        'bank_id' => $requestData->cheque_bank,
                        'amount' => $requestData->cheque_amount,
                        'account_number' => $requestData->cheque_account_number,
                        'number' => $requestData->cheque_number,
                        'payment_date' => $requestData->cheque_date,
                    ]];
                    break;
                case "6":
                    //  Cheque & Demand Draft
                    return [[
                        'payment_type'=>$type,
                        'policy_id'=> $policy_id,
                        'bank_id' => $requestData->cheque_bank,
                        'amount' => $requestData->cheque_amount,
                        'account_number' => $requestData->cheque_account_number,
                        'number' => $requestData->cheque_number,
                        'payment_date' => $requestData->cheque_date,
                    ],[
                        'payment_type'=>$type,
                        'policy_id'=> $policy_id,
                        'bank_id' => $requestData->dd_bank,
                        'amount' => $requestData->dd_amount,
                        'account_number' => $requestData->dd_account_number,
                        'number' => $requestData->dd_number,
                        'payment_date' => $requestData->dd_date,
                    ]];
                    break;
                case "7":
                    //  Cash & Demand Draft
                    return [[
                        'payment_type'=>$type,
                        'policy_id'=> $policy_id,
                        'bank_id' => null,
                        'amount' => (isset($requestData) && isset($requestData->cash_amount) ? $requestData->cash_amount : 0),
                        'account_number' => null,
                        'number' => null,
                        'payment_date' => null,
                    ],[
                        'payment_type'=>$type,
                        'policy_id'=> $policy_id,
                        'bank_id' => $requestData->dd_bank,
                        'amount' => $requestData->dd_amount,
                        'account_number' => $requestData->dd_account_number,
                        'number' => $requestData->dd_number,
                        'payment_date' => $requestData->dd_date,
                    ]];
                    break;
                case "8":
                    //  Cash & Online payment
                    return [[
                        'payment_type'=>$type,
                        'policy_id'=> $policy_id,
                        'bank_id' => null,
                        'amount' => (isset($requestData) && isset($requestData->cash_amount) ? $requestData->cash_amount : 0),
                        'account_number' => null,
                        'number' => null,
                        'payment_date' => null,
                    ],[
                        'payment_type'=>$type,
                        'policy_id'=> $policy_id,
                        'bank_id' => $requestData->transaction_bank,
                        'amount' => $requestData->online_amount,
                        'account_number' => null,
                        'number' => $requestData->transaction_number,
                        'payment_date' => $requestData->transaction_date,
                    ]];
                    break;
                case "9":
                    //   Online payment & Cheque
                    return [[
                        'payment_type'=>$type,
                        'policy_id'=> $policy_id,
                        'bank_id' => $requestData->transaction_bank,
                        'amount' => $requestData->online_amount,
                        'account_number' => null,
                        'number' => $requestData->transaction_number,
                        'payment_date' => $requestData->transaction_date,
                        'payment_type' => $type,
                    ],[
                        'payment_type'=>$type,
                        'policy_id'=> $policy_id,
                        'bank_id' => $requestData->cheque_bank,
                        'amount' => $requestData->cheque_amount,
                        'account_number' => $requestData->cheque_account_number,
                        'number' => $requestData->cheque_number,
                        'payment_date' => $requestData->cheque_date,
                    ]
                    ];
                    break;
                case "10":
                    //   Online payment & Demand Draft
                    return [[
                        'payment_type'=>$type,
                        'policy_id'=> $policy_id,
                        'bank_id' => $requestData->transaction_bank,
                        'amount' => $requestData->online_amount,
                        'account_number' => null,
                        'number' => $requestData->transaction_number,
                        'payment_date' => $requestData->transaction_date,
                    ],[
                        'payment_type'=>$type,
                        'policy_id'=> $policy_id,
                        'bank_id' => $requestData->dd_bank,
                        'amount' => $requestData->dd_amount,
                        'account_number' => $requestData->dd_account_number,
                        'number' => $requestData->dd_number,
                        'payment_date' => $requestData->dd_date,
                    ]];
                    break;
                default:
                   return false;
            }
        }
    }
    if(!function_exists('csvToArray')){
        function csvToArray($filename = '', $delimiter = ',')
        {
            if (!file_exists($filename) || !is_readable($filename))
                return false;

            $header = null;
            $data = array();
            if (($handle = fopen($filename, 'r')) !== false)
            {
                while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
                {
                    if (!$header)
                        $header = $row;
                    else
                        $data[] = array_combine($header, $row);
                }
                fclose($handle);
            }

            return $data;
        }
    }
    function age($dob) {
        $now = new DateTime();
        $dob = new DateTime($dob);
        $difference = $now->diff($dob);
        return $difference->y;
    }
    function inwardFirstChar($type) {
        
        switch ($type) {
            case 'fresh':
              $data='F';
            break;
            case 'renewal':
                $data='R';
            break;
            case 'port fresh':
                $data='PF';
            break;
            case 'port renewal':
                $data='PR';
            break;
            default:
                $data='CA';
            break;
        }
        return $data;
    }
    function getNextCode($oldCode){
        if(empty($oldCode)){
            return 'AA';
        }
        return ++$oldCode;
    }
    function getSettingValueByKey($key){
        return Settings::select('value')->where('key',$key)->first();
    }
    function generateAgentCode($code) {
        $a=substr($code,0,2);
        $b=substr($code,2,2);
        substr($code,0,2)==99?++$b:++$a;
        return str_pad($a,2,'0',STR_PAD_LEFT).$b;
    }
    function getFirstString($string){
        $words = explode(" ", $string);
        $acronym = "";
        
        foreach ($words as $w) {
          $acronym .= mb_substr($w, 0, 1);
        }
        return $acronym;
    }
    function relations(){
        $data = ['Self','Father','Father In Law','Mother','Mother in Law','Husband','Wife','Brother','Sister','Son','Daughter','Daughter in Law','Son in Law'];
        return $data;
    }
    function tenures(){
        $tenures = ['1','2','3','4','5','6','7','8','9','10','15','SHORT','ABOVE15YRS'];
        return $tenures;
    }
    if(!function_exists('query')) {
        function query($query) {
            $sql = str_replace(array('?'), array('\'%s\''), $query->toSql());
            $query = vsprintf($sql, $query->getBindings());
            dd($query);
        }
    }
    if(!function_exists('businessYear')) {
        function businessYear($date) {
            try{
                return Carbon::createFromFormat('m-Y', $date)->format('Y');
            }catch(\Throwable $e){
                return $date;
            }
        }
    }
    if(!function_exists('businessMonth')) {
        function businessMonth($date) {
            try{
                return Carbon::createFromFormat('m-Y', $date)->format('M');
            }catch(\Throwable $e){
                return $date;
            }
        }
    }

    if(!function_exists('vehicleNO')) {
        function vehicleNO($data) {        
            try{
                return str_replace("_","",$data);
            }catch(\Throwable $e){
                return ' ';
            }
        }
    }
    if(!function_exists('realVehicleNo')) {
        function realVehicleNo($data) {         
            try{
              switch (strlen($data)) {
                    case 11:
                        return $data;
                        break;
                    case 10:
                        return substr_replace($data, '_', 6, 0);
                        break;
                    case 9:
                        return substr_replace($data, '__', 5, 0);
                        break;
                    default: 
                        return $data;
                }
            }catch(\Throwable $e){
                return $data;
            }
        }
    }
    if(!function_exists('motopolicy_data')) {
        function motopolicy_data($request){
            $motorPolicy = MotorPolicy::where('status','=',1)->where('policy_number',null)->with('product','sub_product','customer','branch','company','companyBranch','branch_imd_name');
            if($request->ajax() && !empty($request->all())){
                if(isset($request->agent)){
                    $motorPolicy->where('agent_id',$request->agent);
                }
                if(isset($request->name)){
                    $motorPolicy->whereHas('customer', function ($query) use ($request) {
                        $query->where('first_name', 'like', '%'.$request->name.'%')
                        ->orWhere('middle_name', 'like', '%'.$request->name.'%')
                        ->orWhere('last_name', 'like', '%'.$request->name.'%');
                });
                }
                if(isset($request->branch)){
                    $motorPolicy->where('irss_branch_id',$request->branch);
                }
                if(isset($request->company)){
                    $motorPolicy->whereHas('company', function ($queryData) use ($request) {
                        $queryData->where('id',$request->company);
                    });
                }
                if(isset($request->cheque_no)){
                    $motorPolicy->whereHas('payments', function ($query) use ($request) {
                        $query->where('payment_type', 2)->where('number', $request->cheque_no);
                    });
                }
                if(isset($request->inward_no)){
                    $motorPolicy->where('inward_no',$request->inward_no);
                }
                if(isset($request->policy_no)){
                    $motorPolicy->where('policy_number',$request->policy_no);
                }
                if(isset($request->product)){
                    $motorPolicy->where('product_id',$request->product);
                }
                if(isset($request->policy_no)){
                    $motorPolicy->where('policy_number',$request->policy_no);
                }
                if(isset($request->engine_no)){
                    $motorPolicy->whereHas('motor_policy_vehicle', function ($query) use ($request) {
                        $query->where('engine_no', $request->engine_no);
                    });

                }
                if(isset($request->chasis_no)){
                    $motorPolicy->whereHas('motor_policy_vehicle', function ($query) use ($request) {
                        $query->where('chasiss_no', $request->chasis_no);
                    });
                }
                if(isset($request->registration_no)){
                    $motorPolicy->whereHas('motor_policy_vehicle', function ($query) use ($request) {
                        $query->where('registration_no', $request->registration_no);
                    });
                }
                if (isset($request->policy_start_date) && isset($request->policy_end_date)) {
                    $motorPolicy->where('start_date', '<=', $request->policy_start_date)->where('end_date', '>=', $request->policy_end_date);
                }else{
                    if(isset($request->policy_start_date)){
                        $motorPolicy->where('start_date',$request->policy_start_date);
                    }
                    if(isset($request->policy_end_date)){
                        $motorPolicy->where('end_date',$request->policy_end_date);
                    }
                }

                if (isset($request->start_date) && isset($request->end_date)) {

                    $from_date = Carbon::parse($request->start_date)->format('Y-m-d 00:00:00');
                    $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                    $motorPolicy->whereBetween('created_at', [$from_date, $to_date]);
                }else{
                    if(isset($request->start_date)){
                        $from_date = Carbon::parse($request->start_date)->format('Y-m-d 00:00:00');
                        $motorPolicy->where('created_at',$from_date);
                    }
                    if(isset($request->end_date)){
                        $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                        $motorPolicy->where('created_at',$to_date);
                    }
                }
            }
            return $motorPolicy;
        }                               
    }
    if(!function_exists('healthpolicy_data')) {
        function healthpolicy_data($request){
            $users = HealthPolicy::query()->where('status','=',1)->where('policy_number',null)->with('product','customer','company_branch','company','payments','agent');
            if($request->ajax() && !empty($request->all())){
                if(isset($request->agent)){
                    $users->where('agent_id',$request->agent);
                }
                if(isset($request->name)){
                    $users->whereHas('customer', function ($query) use ($request) {
                        $query->where('first_name', 'like', '%'.$request->name.'%')
                        ->orWhere('middle_name', 'like', '%'.$request->name.'%')
                        ->orWhere('last_name', 'like', '%'.$request->name.'%');
                    });
                }
                if(isset($request->branch)){
                    $users->where('irss_branch_id',$request->branch);
                }
                if(isset($request->company)){
                    $users->whereHas('company_branch', function ($query) use ($request) {
                        $query->whereHas('company', function ($queryData) use ($request) {
                            $queryData->where('id',$request->company);
                        });
                    });
                }
                if(isset($request->cheque_no)){
                    $users->whereHas('health_policy_payments', function ($query) use ($request) {
                        $query->where('payment_type', 2)->where('number', $request->cheque_no);
                    });
                }
                if(isset($request->inward_no)){
                    $users->where('inward_no',$request->inward_no);
                }
                if(isset($request->policy_no)){
                    $users->where('policy_number',$request->policy_no);
                }
                if(isset($request->product)){
                    $users->whereHas('product', function ($query) use ($request) {
                        $query->where('id', $request->product);
                    });
                }
    
                if (isset($request->policy_start_date) && isset($request->policy_end_date)) {
                    $users->where('start_date', '<=', $request->policy_start_date)->where('end_date', '>=', $request->policy_end_date);
                }else{
                    if(isset($request->policy_start_date)){
                        $users->where('start_date',$request->policy_start_date);
                    }
                    if(isset($request->policy_end_date)){
                        $users->where('end_date',$request->policy_end_date);
                    }
                }
    
                if (isset($request->start_date) && isset($request->end_date)) {
    
                    $from_date = Carbon::parse($request->start_date)->format('Y-m-d 00:00:00');
                    $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                    $users->whereBetween('created_at', [$from_date, $to_date]);
                }else{
                    if(isset($request->start_date)){
                        $from_date = Carbon::parse($request->start_date)->format('Y-m-d 00:00:00');
                        $users->where('created_at',$from_date);
                    }
                    if(isset($request->end_date)){
                        $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                        $users->where('created_at',$to_date);
                    }
                }
            }
            return $users;
        }
    }
    if(!function_exists('smepolicy_data')) {
        function smepolicy_data($request){
            $sme_policies = SmePolicy::where('status','=',1)->where('policy_number',null)->with('product','customer','companyBranch','payments','agent','company');
            if($request->ajax() && !empty($request->all())){
                if(isset($request->agent)){
                    $sme_policies->where('agent_id',$request->agent);
                }
                if(isset($request->name)){
                    $sme_policies->whereHas('customer', function ($query) use ($request) {
                        $query->where('first_name', 'like', '%'.$request->name.'%')
                        ->orWhere('middle_name', 'like', '%'.$request->name.'%')
                        ->orWhere('last_name', 'like', '%'.$request->name.'%');
                       });
                }
                if(isset($request->branch)){
                    $sme_policies->where('irss_branch_id',$request->branch);
                }
                if(isset($request->company)){
                    $sme_policies->whereHas('company', function ($queryData) use ($request) {
                        $queryData->where('id',$request->company);
                    });
                }
                if(isset($request->cheque_no)){
                    $sme_policies->whereHas('payments', function ($query) use ($request) {
                        $query->where('payment_type', 2)->where('number', $request->cheque_no);
                    });
                }
                if(isset($request->inward_no)){
                    $sme_policies->where('inward_no',$request->inward_no);
                }
                if(isset($request->policy_no)){
                    $sme_policies->where('policy_number',$request->policy_no);
                }
                if(isset($request->product)){
                    $sme_policies->whereHas('product', function ($query) use ($request) {
                        $query->where('id', $request->product);
                    });
                }
    
                if (isset($request->policy_start_date) && isset($request->policy_end_date)) {
                    $sme_policies->where('start_date', '<=', $request->policy_start_date)->where('end_date', '>=', $request->policy_end_date);
                }else{
                    if(isset($request->policy_start_date)){
                        $sme_policies->where('start_date',$request->policy_start_date);
                    }
                    if(isset($request->policy_end_date)){
                        $sme_policies->where('end_date',$request->policy_end_date);
                    }
                }
    
                if (isset($request->start_date) && isset($request->end_date)) {
    
                    $from_date = Carbon::parse($request->start_date)->format('Y-m-d 00:00:00');
                    $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                    $sme_policies->whereBetween('created_at', [$from_date, $to_date]);
                }else{
                    if(isset($request->start_date)){
                        $from_date = Carbon::parse($request->start_date)->format('Y-m-d 00:00:00');
                        $sme_policies->where('created_at',$from_date);
                    }
                    if(isset($request->end_date)){
                        $to_date = Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
                        $sme_policies->where('created_at',$to_date);
                    }
                }
            }
            return $sme_policies;
        }
    }
