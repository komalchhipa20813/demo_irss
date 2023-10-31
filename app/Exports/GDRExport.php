<?php

namespace App\Exports;

use App\Models\{MotorPolicy,HealthPolicy,SmePolicy};
use Carbon\Carbon;
use DateTime;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class GDRExport implements FromCollection, ShouldAutoSize,WithEvents, WithHeadings, WithCustomStartCell
{
    private $insurance,$from_date, $end_date,$expiry_from_date,$expiry_end_date,$collection,$fdo,$agent,$company,$company_branch_name,$product,$branch,$date_formate;
	function __construct($insurance,$from_date, $end_date,$expiry_from_date,$expiry_end_date,$fdo,$agent,$company,$company_branch_name,$product,$branch,$date_formate) { 
		$this->insurance=$insurance;
		$this->from_date=$from_date;
		$this->end_date=$end_date;
		$this->expiry_from_date=$expiry_from_date;
		$this->expiry_end_date=$expiry_end_date;
		$this->fdo=$fdo;
		$this->agent=$agent;
		$this->company=$company;
		$this->company_branch_name=$company_branch_name;
		$this->product=$product;
		$this->branch=$branch;
		$this->date_formate=$date_formate;
	}

	/**
	 * @return \Illuminate\Support\Collection
	 */
	public function startCell(): string {
		return 'A1';
	}

	public function registerEvents(): array{

		return [
			AfterSheet::class => function (AfterSheet $event) {
				/** @var Sheet $sheet */
				$sheet = $event->sheet;


				$cellRange = 'A1:CC1'; // All headers
				$event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
				$event->sheet->getDelegate()->getStyle('A1:CC1')->getFont()
					->setBold(true);

			 	$event->sheet->getDelegate()->getStyle('A1:CC1')
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('FFFF00');
                $event->sheet->setAutoFilter('A1:CC1');
				$event->sheet->getDelegate()->getStyle('A1:CC1048576')
				->getAlignment()
				->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

				$styleArray = [
					'alignment' => [
						'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
					],
				];
			},
		];
	}
	public function collection()
    {
		$data=[];
    	if($this->insurance == 'MOTOR' || $this->insurance == 'ALL')
    	{
    		$motor_query= MotorPolicy::where('status','!=',2)->with('product','sub_product','customer','branch','company','companyBranch','branch_imd_name','payments','agent','motor_policy_vehicle','product_type','previous_company','renewal_previous_company');
			
			if($this->from_date != '' && $this->end_date != '')
			{
				$from_date = Carbon::createFromFormat('d-m-Y', $this->from_date)->format('Y-m-d 00:00:00');
				$to_date = Carbon::createFromFormat('d-m-Y',$this->end_date)->format('Y-m-d 23:59:59');
				$motor_query->whereBetween('created_at', [$from_date, $to_date]);
			}
			if($this->expiry_from_date != '' && $this->expiry_end_date != '')
			{
				$from_date = Carbon::parse($this->expiry_from_date)->format('Y-m-d 00:00:00');
				$to_date = Carbon::parse($this->expiry_end_date)->format('Y-m-d 23:59:59');
				$motor_query->whereBetween(DB::raw("STR_TO_DATE(end_date,'%d-%m-%Y')"), [$from_date, $to_date]);
			}
			if($this->branch != '')
			{
				$motor_query->where('irss_branch_id',$this->branch);
			}
			if($this->company != '')
			{
				$motor_query->where('company_id',$this->company);
			}
			if($this->company_branch_name != '')
			{
				$motor_query->where('company_branch_id',$this->company_branch_name);
			}
			if($this->agent != '')
			{
				$motor_query->where('agent_id',$this->agent);
			}

			if($this->fdo != ''){
				$motor_query->whereHas('agent', function ($query){
					$query->where('fdo_id', $this->fdo);
				});
			}
			$motorPolicys=$motor_query->oldest()->get();
			if($motorPolicys->isNotEmpty()) 
			{
				foreach ($motorPolicys as $key => $motorPolicy) 
				{
					$payments=$motorPolicy->payments;
					$bank_name='';
					$account_no='';
					$check_no='';
					$check_date='';
					$amount=0;
					$bank_name_2='';
					$account_no_2='';
					$check_no_2='';
					$check_date_2='';
					$amount_2=0;
					foreach ($payments as $key => $payment) 
					{
						if($key == 1)
						{
							$bank_name_2=!empty($payment->bank)?$payment->bank->name:'';
							$account_no_2=$payment->account_number;
							$check_no_2=$payment->number;
							$check_date_2=$payment->payment_date;
							$amount_2=$payment->amount;
						}else{
							$bank_name=(!empty($payment->bank_id))?$payment->bank->name:'';
							$account_no=$payment->account_number;
							$check_no=$payment->number;
							$check_date=$payment->payment_date;
							$amount=$payment->amount;
						}
					}
					$od=(!empty($motorPolicy->od))?$motorPolicy->od :0;
					$add_on=(!empty($motorPolicy->addonpremium))?$motorPolicy->addonpremium :0;
					$total_od=round($od + $add_on);
					$tp=(!empty($motorPolicy->tp))?$motorPolicy->tp :0;
					$pay_to_owner=(!empty($motorPolicy->pay_to_owner))?$motorPolicy->pay_to_owner :0;
					$total_tp_or_ter=round($tp+$pay_to_owner);
					$net_premium=round($total_od + $total_tp_or_ter);
					$stamp_duty_value=(!empty($motorPolicy->stamp_duty))?$motorPolicy->stamp_duty :0;
					$total_premium=(!empty($motorPolicy->total_premium))?$motorPolicy->total_premium :0;
					$gst_value=round(($total_premium-$stamp_duty_value) - $net_premium);
					$Previous_Policy_No=!empty($motorPolicy->previous_policy_number)?$motorPolicy->previous_policy_number:(!empty($motorPolicy->renewal_previous_policy_number)?$motorPolicy->renewal_previous_policy_number:'');
					$Previous_Policy_company=!empty($motorPolicy->previous_company)?$motorPolicy->previous_company->name:(!empty($motorPolicy->renewal_previous_company)?$motorPolicy->renewal_previous_company->name:'');
					// dd($motorPolicy->motor_policy_vehicle,'hii');
					$data[]=[
						'business_year' => businessYear($motorPolicy->business_date),
						'business_month' => businessMonth($motorPolicy->business_date),
						'inward_no' => $motorPolicy->inward_no,
						'start_date' => date_format_date($motorPolicy->created_at),
						'irss_branch_id' => $motorPolicy->branch->name,
						'fdo_code' => $motorPolicy->agent->fdo->code ,
						'agent_code' => $motorPolicy->agent->code,
						'customer_id'=>$motorPolicy->customer->customer_code,
						'prefix' => $motorPolicy->customer->prefix,
						'customer_name' => $motorPolicy->customer->first_name .' '. $motorPolicy->customer->middle_name .' '. $motorPolicy->customer->last_name,
						'company_name' => $motorPolicy->company->name,
						'company_branch_name' => (!empty($motorPolicy->company_branch_id ))?$motorPolicy->companyBranch->name : '',
						'CODE_Type' =>($motorPolicy->code_type == 1 )? 'AGENCY' : 'BROKER',
						'IMD_code_name' => (!empty($motorPolicy->branch_imd_id))?$motorPolicy->branch_imd_name->name : '',
						'Sector' => 'Private',
						'Insurance_Type' => 'General Insurance',
						'main_product_name' => 'MOTOR',
						'product_name' => !empty($motorPolicy->product)?$motorPolicy->product->name:null,
						'sub_product_name' => (!empty($motorPolicy->sub_product_id))? $motorPolicy->sub_product->name : '',
						'product_type' => !empty($motorPolicy->product_type)?$motorPolicy->product_type->type:'',
						'policy_tenure' => $motorPolicy->policy_tenure,
						'policy_type' => ucwords($motorPolicy->policy_type),
						'registration_no' => !empty($motorPolicy->motor_policy_vehicle)?vehicleNO($motorPolicy->motor_policy_vehicle->registration_no):null,

						'rto_code' => (!empty($motorPolicy->motor_policy_vehicle) && !empty($motorPolicy->motor_policy_vehicle->city_id))?$motorPolicy->motor_policy_vehicle->rto_city->rto_code:null,
						'rto_city' => (!empty($motorPolicy->motor_policy_vehicle) && !empty($motorPolicy->motor_policy_vehicle->city_id))?$motorPolicy->motor_policy_vehicle->rto_city->name:null,
						'engine_no' => !empty($motorPolicy->motor_policy_vehicle)?$motorPolicy->motor_policy_vehicle->engine_no:null,
						'chasiss_no' => !empty($motorPolicy->motor_policy_vehicle)?$motorPolicy->motor_policy_vehicle->chasiss_no:null,
						'make_id' => (!empty($motorPolicy->motor_policy_vehicle->make_id)) ? $motorPolicy->motor_policy_vehicle->make->name :'',
						'model_id' => (!empty($motorPolicy->motor_policy_vehicle->model_id )) ? $motorPolicy->motor_policy_vehicle->product_model->name : '',
						'variant_id' => (!empty($motorPolicy->motor_policy_vehicle->variant_id)) ? $motorPolicy->motor_policy_vehicle->product_variant->name : '',
						'cc_gvw_no' => !empty($motorPolicy->motor_policy_vehicle)?$motorPolicy->motor_policy_vehicle->cc_gvw_no:null,
						'manufacturing_year' => !empty($motorPolicy->motor_policy_vehicle)?$motorPolicy->motor_policy_vehicle->manufacturing_year:null,
						'seating_capacity' => !empty($motorPolicy->motor_policy_vehicle)?$motorPolicy->motor_policy_vehicle->seating_capacity:null,
						'fuel_type' => !empty($motorPolicy->motor_policy_vehicle)?$motorPolicy->motor_policy_vehicle->fuel_type:null,
						'total_idv' => !empty($motorPolicy->total_idv)?$motorPolicy->total_idv:'0',
						'discount' => !empty($motorPolicy->discount)?$motorPolicy->discount:'0',
						'ncb' => !empty($motorPolicy->ncb)?$motorPolicy->ncb:'0',
						'od' => !empty($od)?$od:'0',
						'add_on' => !empty($add_on)?$add_on:'0',
						'total_od' => !empty($total_od)?$total_od:'0',
						'tp' => !empty($tp)?$tp:'0',
						'pay_to_owner' => !empty($pay_to_owner)?$pay_to_owner:'0',
						'terrorism' =>'0',
						'total_terrorism' => !empty($total_tp_or_ter) ?$total_tp_or_ter:'0',
						'net_premium' => $net_premium,
						'gst' => $motorPolicy->is_gst_value==1?$motorPolicy->gst:'',
						'gst_value' => $gst_value,
						'stamp_duty' => $stamp_duty_value,
						'gross_premium' => round($net_premium+$gst_value+$stamp_duty_value),
						'policy_issue_date' => (!empty($this->date_formate))? date_format_date($motorPolicy->issue_date) : $motorPolicy->issue_date,
						'OD_policy_start_date' => (!empty($this->date_formate))? date_format_date($motorPolicy->start_date) : $motorPolicy->start_date,
						'OD_policy_end_date' => (!empty($this->date_formate))? date_format_date($motorPolicy->end_date) : $motorPolicy->end_date,
						// 'TP_tenure' => $motorPolicy->policy_tenure,
						'TP_start_date' => !empty($motorPolicy->motor_policy_vehicle)?(!empty($this->date_formate)? date_format_date($motorPolicy->motor_policy_vehicle->tp_start_date) : $motorPolicy->motor_policy_vehicle->tp_start_date):null,
						'TP_end_date' => !empty($motorPolicy->motor_policy_vehicle)?(!empty($this->date_formate)? date_format_date($motorPolicy->motor_policy_vehicle->tp_end_date) : $motorPolicy->motor_policy_vehicle->tp_end_date):null,
						'policy_no' =>  !empty($motorPolicy->policy_number) ? "'".$motorPolicy->policy_number : null ,
						'Payment Type'=> !($payments->isEmpty())?get_payment_type($payments[0]->payment_type):null,
						'bank_name' => $bank_name,
						'account_number' => (!empty($account_no))? "'".$account_no :'',
						'Cheque_no' => $check_no,
						'Cheque_date' => (!empty($this->date_formate))? date_format_date($check_date) : $check_date,
						'amount' => $amount+$amount_2,
						'bank_name_2' => $bank_name_2,
						'account_number_2' => (!empty($account_no_2))? "'".$account_no_2 :'',
						'Cheque_no_2' => $check_no_2,
						'Cheque_date_2' => (!empty($this->date_formate))? date_format_date($check_date_2) : $check_date_2,
						'amount_2' => (!empty($amount_2))?$amount_2:'0',
						'Occupancies' => '',
						'Outward_GeneratedDt' =>!empty($motorPolicy->outward)? $motorPolicy->outward->created_at->format('d/m/Y'):'',
						'Previous_Policy_No' =>(!empty($Previous_Policy_No))?  "'".$Previous_Policy_No :'',
						'Previous_Policy_company' => $Previous_Policy_company,
						'Proposal_DOB' => '',
						'Proposal_Age' => '0',
						'Remark' => $motorPolicy->remark,
						'created_by' => !empty($motorPolicy->created_by)?$motorPolicy->created_by->first_name:'',
						'updated_at' => $motorPolicy->updated_at->format('d/m/Y'),
						'updated_by' => !empty($motorPolicy->updated_by)?$motorPolicy->updated_by->first_name:'',
						'updatede_by_date' => $motorPolicy->updated_at->format('d/m/Y'),
						'edited_by' => !empty($motorPolicy->edited_by)?(ucwords( $motorPolicy->edited_by->first_name .' '. $motorPolicy->edited_by->last_name )):'',
						'edited_at' => (!empty($motorPolicy->edited_at))? Carbon::parse($motorPolicy->edited_at)->format('d/m/Y') : '',
						'policy_cancel_reason' => $motorPolicy->policy_cancel_reason,
						'policy_cancel_remark' => $motorPolicy->policy_cancel_remark,
					];
				}
	    	}
    	}

    	//Health Policy
    	if($this->insurance == 'HEALTH' || $this->insurance == 'ALL')
    	{
			$health_query= HealthPolicy::query()->where('status','!=',2)->with('product','sub_product','customer','branch','company_branch','company','branch_imd_name','payments','agent','previous_company','renewal_previous_company');
			if($this->from_date != '' && $this->end_date != '')
			{
				$from_date = Carbon::createFromFormat('d-m-Y', $this->from_date)->format('Y-m-d 00:00:00');
				$to_date = Carbon::createFromFormat('d-m-Y',$this->end_date)->format('Y-m-d 23:59:59');
				$health_query->whereBetween('created_at', [$from_date, $to_date]);
			}
			if($this->expiry_from_date != '' && $this->expiry_end_date != '')
			{
				$from_date = Carbon::parse($this->expiry_from_date)->format('Y-m-d 00:00:00');
				$to_date = Carbon::parse($this->expiry_end_date)->format('Y-m-d 23:59:59');
				$health_query->whereBetween(DB::raw("STR_TO_DATE(end_date,'%d-%m-%Y')"), [$from_date, $to_date]);
			}
			if($this->branch != '')
			{
				$health_query->where('irss_branch_id',$this->branch);
			}
			if($this->company != '')
			{
				$health_query->where('company_id',$this->company);
			}
			if($this->company_branch_name != '')
			{
				$health_query->where('company_branch_id',$this->company_branch_name);
			}
			if($this->agent != '')
			{
				$health_query->where('agent_id',$this->agent);
			}
			if($this->fdo != ''){
				$health_query->whereHas('agent', function ($query){
					$query->where('fdo_id', $this->fdo);
				});
			}
			$healthPolicys=$health_query->oldest()->get();
    	  	if($healthPolicys->isNotEmpty()) {
            	foreach ($healthPolicys as $key => $dataPolicy) {
	            	$payments=$dataPolicy->payments;
	            	$bank_name='';
	            	$account_no='';
	            	$check_no='';
	            	$check_date='';
	            	$amount=0;
	            	$bank_name_2='';
	            	$account_no_2='';
	            	$check_no_2='';
	            	$check_date_2='';
	            	$amount_2=0;

	            	foreach ($payments as $key => $payment) 
	            	{
						if($key == 1)
						{
							$bank_name_2=$payment->bank->name;
			            	$account_no_2=$payment->account_number;
			            	$check_no_2=$payment->number;
			            	$check_date_2=$payment->payment_date;
			            	$amount_2=$payment->amount;
						}
						else{
							$bank_name=(!empty($payment->bank_id))?$payment->bank->name:'';
							$account_no=$payment->account_number;
							$check_no=$payment->number;
							$check_date=$payment->payment_date;
							$amount=$payment->amount;
						}
	            	}
	            	$od=(!empty($dataPolicy->od))?$dataPolicy->od :0;
					$add_on=0;
	                $total_od=round($od + $add_on);
	            	$tp=0;
					$pay_to_owner=0;
					$total_tp_or_ter=round($tp+$pay_to_owner);
					$net_premium=round($total_od + $total_tp_or_ter);
					$stamp_duty_value=(!empty($dataPolicy->stamp_duty))?$dataPolicy->stamp_duty :0;
					$total_premium=(!empty($dataPolicy->total_premium))?$dataPolicy->total_premium :0;
					$gst_value=round(($total_premium-$stamp_duty_value) - $net_premium);
	                $age=0;
					$Previous_Policy_No=!empty($dataPolicy->previous_policy_number)?$dataPolicy->previous_policy_number:(!empty($dataPolicy->renewal_previous_policy_number)?$dataPolicy->renewal_previous_policy_number:'');
					$Previous_Policy_company=!empty($dataPolicy->previous_company)?$dataPolicy->previous_company->name:(!empty($dataPolicy->renewal_previous_company)?$dataPolicy->renewal_previous_company->name:'');
	                if(!empty($dataPolicy->proposal_dob))
	                {
						try{
							$age= date_diff(date_create($dataPolicy->proposal_dob), date_create('today'))->y;
						}catch(\Throwable $e){
							// return ' ';
						}
	                }
					$data[]=[
						'business_year' => businessYear($dataPolicy->business_date),
						'business_month' => businessMonth($dataPolicy->business_date),
						'inward_no' => $dataPolicy->inward_no,
						'start_date' => date_format_date($dataPolicy->created_at),
						'irss_branch_id' => $dataPolicy->branch->name,
						'fdo_code' => $dataPolicy->agent->fdo->code,
						'agent_code' => $dataPolicy->agent->code,
						'customer_id'=>$dataPolicy->customer->customer_code,
						'prefix' => $dataPolicy->customer->prefix,
						'customer_name' => $dataPolicy->customer->first_name .' '. $dataPolicy->customer->middle_name .' '. $dataPolicy->customer->last_name,
						'company_name' => $dataPolicy->company->name,
						'company_branch_name' =>(!empty($dataPolicy->company_branch_id)) ? $dataPolicy->company_branch->name : '',
						'CODE_Type' => ($dataPolicy->code_type == 1 )? 'AGENCY' : 'BROKER',
						'IMD_code_name' => (!empty($dataPolicy->branch_imd_id )) ?$dataPolicy->branch_imd_name->name:'',
						'Sector' => 'Private',
						'Insurance_Type' => 'General Insurance',
						'main_product_name' => 'HEALTH',
						'product_name' => !empty($dataPolicy->product)?$dataPolicy->product->name:null,
						'sub_product_name' => (!empty($dataPolicy->sub_product_id))? $dataPolicy->sub_product->name : '',
						'product_type' => $dataPolicy->product_type==0?'Floater':'Individual',
						'policy_tenure' => $dataPolicy->policy_tenure,
						'policy_type' => ucwords($dataPolicy->policy_type),
						'registration_no' => '',
						'rto_code' => '',
						'rto_city' => '',
						'engine_no' => '',
						'chasiss_no' => '',
						'make_id' => '',
						'model_id' => '',
						'variant_id' => '',
						'cc_gvw_no' => '',
						'manufacturing_year' => '',
						'seating_capacity' => '',
						'fuel_type' => '',
						'total_idv' => !empty($dataPolicy->sum_insured)?$dataPolicy->sum_insured:'0',
						'discount' => '0',
						'ncb' => '0',
						'od' => !empty($od)?$od:'0',
						'add_on' => !empty($add_on)?$add_on:'0',
						'total_od' => !empty($total_od)?$total_od:'0',
						'tp' => !empty($tp)?$tp:'0',
						'pay_to_owner' => !empty($pay_to_owner)?$pay_to_owner:'0',
						'terrorism' =>'0',
						'total_terrorism' => !empty($total_tp_or_ter) ?$total_tp_or_ter:'0',
						'net_premium' => $net_premium,
						'gst' => $dataPolicy->is_gst_value==1?$dataPolicy->gst:'',
						'gst_value' => $gst_value,
						'stamp_duty' => $stamp_duty_value,
						'gross_premium' => round($net_premium+$gst_value+$stamp_duty_value),
						'policy_issue_date' => (!empty($this->date_formate))? date_format_date($dataPolicy->issue_date) : $dataPolicy->issue_date,
						'OD_policy_start_date' => (!empty($this->date_formate))? date_format_date($dataPolicy->start_date) : $dataPolicy->start_date,
						'OD_policy_end_date' => (!empty($this->date_formate))? date_format_date($dataPolicy->end_date) : $dataPolicy->end_date,
						// 'TP_tenure' => $dataPolicy->policy_tenure,
						'TP_start_date' => '',
						'TP_end_date' => '',
						'policy_no' => !empty($dataPolicy->policy_number) ?  "'".$dataPolicy->policy_number : null,
						'Payment Type'=> !($payments->isEmpty())?get_payment_type($payments[0]->payment_type):null,
						'bank_name' => $bank_name,
						'account_number' => (!empty($account_no))? "'".$account_no :'',
						'Cheque_no' => $check_no,
						'Cheque_date' => (!empty($this->date_formate))? date_format_date($check_date): $check_date,
						'amount' => $amount+$amount_2,
						'bank_name_2' => $bank_name_2,
						'account_number_2' => (!empty($account_no_))? "'".$account_no_2 : '',
						'Cheque_no_2' => $check_no_2,
						'Cheque_date_2' => (!empty($this->date_formate))? date_format_date($check_date_2) : $check_date_2,
						'amount_2' => (!empty($amount_2))?$amount_2:'0',
						'Occupancies' => '',
						'Outward_GeneratedDt' =>!empty($dataPolicy->outward)? $dataPolicy->outward->created_at->format('d/m/Y'):'',
						'Previous_Policy_No' => (!empty($Previous_Policy_No))?  "'".$Previous_Policy_No :'' ,
						'Previous_Policy_company' => $Previous_Policy_company,
						'Proposal_DOB' => (!empty($this->date_formate))? date_format_date($dataPolicy->proposal_dob) : $dataPolicy->proposal_dob ,
						'Proposal_Age' => (!empty($age))?$age:'0',
						'Remark' => $dataPolicy->remark,
						'created_by' => !empty($dataPolicy->created_by)?$dataPolicy->created_by->first_name:'',
						'updated_at' => $dataPolicy->updated_at->format('d/m/Y'),
						'updated_by' => !empty($dataPolicy->updated_by)?$dataPolicy->updated_by->first_name:'',
						'updatede_by_date' => $dataPolicy->updated_at->format('d/m/Y'),
						'edited_by' => !empty($dataPolicy->edited_by)?(ucwords( $dataPolicy->edited_by->first_name .' '. $dataPolicy->edited_by->last_name )):'',
						'edited_at' => (!empty($dataPolicy->edited_at))? Carbon::parse($dataPolicy->edited_at)->format('d/m/Y') : '',
						'policy_cancel_reason' => $dataPolicy->policy_cancel_reason,
						'policy_cancel_remark' => $dataPolicy->policy_cancel_remark,
					];
            	}
        	}
    	}
    	//SME Policy
    	 
    	if($this->insurance == 'SME' || $this->insurance == 'ALL')
    	{
    		$sme_query= SmePolicy::where('status','!=',2)->with('product','sub_product','customer','branch','companyBranch','branch_imd_name','payments','agent','company','previous_company','renewal_previous_company');

			if($this->from_date != '' && $this->end_date != '')
			{
				$from_date = Carbon::createFromFormat('d-m-Y', $this->from_date)->format('Y-m-d 00:00:00');
				$to_date = Carbon::createFromFormat('d-m-Y',$this->end_date)->format('Y-m-d 23:59:59');
				$sme_query->whereBetween('created_at', [$from_date, $to_date]);
			}
			if($this->expiry_from_date != '' && $this->expiry_end_date != '')
			{
				$from_date = Carbon::parse($this->expiry_from_date)->format('Y-m-d 00:00:00');
				$to_date = Carbon::parse($this->expiry_end_date)->format('Y-m-d 23:59:59');
				$sme_query->whereBetween(DB::raw("STR_TO_DATE(end_date,'%d-%m-%Y')"), [$from_date, $to_date]);
			}
			if($this->branch != '')
			{
				$sme_query->where('irss_branch_id',$this->branch);
			}
			if($this->company != '')
			{
				$sme_query->where('company_id',$this->company);
			}
			if($this->company_branch_name != '')
			{
				$sme_query->where('company_branch_id',$this->company_branch_name);
			}
			if($this->agent != '')
			{
				$sme_query->where('agent_id',$this->agent);
			}
			if($this->fdo != ''){
				$sme_query->whereHas('agent', function ($query){
					$query->where('fdo_id', $this->fdo);
				});
			}
			$smePolicys=$sme_query->oldest()->get();

    	  	if($smePolicys->isNotEmpty()) {
            	foreach ($smePolicys as $key => $dataPolicy) {
	            	$payments=$dataPolicy->payments;
	            	$bank_name='';
	            	$account_no='';
	            	$check_no='';
	            	$check_date='';
	            	$amount=0;
	            	$bank_name_2='';
	            	$account_no_2='';
	            	$check_no_2='';
	            	$check_date_2='';
	            	$amount_2=0;

	            	foreach ($payments as $key => $payment) 
	            	{
						if($key == 1)
						{
							$bank_name_2=$payment->bank->name;
			            	$account_no_2=$payment->account_number;
			            	$check_no_2=$payment->number;
			            	$check_date_2=$payment->payment_date;
			            	$amount_2=$payment->amount;
						}else{
							$bank_name=(!empty($payment->bank_id))?$payment->bank->name:'';
							$account_no=$payment->account_number;
							$check_no=$payment->number;
							$check_date=$payment->payment_date;
							$amount=$payment->amount;
						}
	            	}
					$od=(!empty($dataPolicy->od))?$dataPolicy->od :0;
					$add_on=0;
	                $total_od=round($od + $add_on);
	            	$tp=(!empty($dataPolicy->terrorism_premium))?$dataPolicy->terrorism_premium :0;
					$pay_to_owner=0;
					$total_tp_or_ter=round($pay_to_owner + $tp);
	            	$net_premium=round($total_od + $total_tp_or_ter);
					$stamp_duty_value=(!empty($dataPolicy->stamp_duty))?$dataPolicy->stamp_duty :0;
					$total_premium=(!empty($dataPolicy->total_premium))?$dataPolicy->total_premium :0;
					$gst_value=round(($total_premium-$stamp_duty_value) - $net_premium);
	                $age=0;
					$Previous_Policy_No=!empty($dataPolicy->previous_policy_number)?$dataPolicy->previous_policy_number:(!empty($dataPolicy->renewal_previous_policy_number)?$dataPolicy->renewal_previous_policy_number:'');
					$Previous_Policy_company=!empty($dataPolicy->previous_company)?$dataPolicy->previous_company->name:(!empty($dataPolicy->renewal_previous_company)?$dataPolicy->renewal_previous_company->name:'');
	                if(!empty($dataPolicy->proposal_dob))
	                {
	                	$age= date_diff(date_create($dataPolicy->proposal_dob), date_create('today'))->y;
	                }
					$data[]=[
						'business_year' => businessYear($dataPolicy->business_date),
						'business_month' => businessMonth($dataPolicy->business_date),
						'inward_no' => $dataPolicy->inward_no,
						'start_date' => date_format_date($dataPolicy->created_at),
						'irss_branch_id' => $dataPolicy->branch->name,
						'fdo_code' => $dataPolicy->agent->fdo->code,
						'agent_code' => $dataPolicy->agent->code,
						'customer_id'=>$dataPolicy->customer->customer_code,
						'prefix' => $dataPolicy->customer->prefix,
						'customer_name' => $dataPolicy->customer->first_name .' '. $dataPolicy->customer->middle_name .' '. $dataPolicy->customer->last_name,
						'company_name' => $dataPolicy->company->name,
						'company_branch_name' => (!empty($dataPolicy->company_branch_id)) ? $dataPolicy->companyBranch->name : '',
						'CODE_Type' =>  ($dataPolicy->code_type == 1 )? 'AGENCY' : 'BROKER',
						'IMD_code_name' => (!empty($dataPolicy->branch_imd_id )) ?$dataPolicy->branch_imd_name->name:'',
						'Sector' => 'Private',
						'Insurance_Type' => 'General Insurance',
						'main_product_name' => 'SME',
						'product_name' => !empty($dataPolicy->product)?$dataPolicy->product->name:null,
						'sub_product_name' => (!empty($dataPolicy->sub_product_id))? $dataPolicy->sub_product->name : '',
						'product_type' => '',
						'policy_tenure' => $dataPolicy->policy_tenure,
						'policy_type' => ucwords($dataPolicy->policy_type),
						'registration_no' => '',
						'rto_code' => '',
						'rto_city' => '',
						'engine_no' => '',
						'chasiss_no' => '',
						'make_id' => '',
						'model_id' => '',
						'variant_id' => '',
						'cc_gvw_no' => '',
						'manufacturing_year' => '',
						'seating_capacity' => '',
						'fuel_type' => '',
						'total_idv' => 	$dataPolicy->sum_insured,
						'discount' => '0',
						'ncb' => '0',
						'od' => !empty($od)?$od:'0',
						'add_on' => !empty($add_on)?$add_on:'0',
						'total_od' => !empty($total_od)?$total_od:'0',
						'tp' => '0',
						'pay_to_owner' => !empty($pay_to_owner)?$pay_to_owner:'0',
						'terrorism' =>!empty($tp)?$tp:'0',
						'total_terrorism' => !empty($total_tp_or_ter) ?$total_tp_or_ter:'0',
						'net_premium' => $net_premium,
						'gst' => $dataPolicy->is_gst_value==1?$dataPolicy->gst:'',
						'gst_value' => $gst_value,
						'stamp_duty' => $stamp_duty_value,
						'gross_premium' => round($net_premium+$gst_value+$stamp_duty_value),
						'policy_issue_date' => (!empty($this->date_formate))? date_format_date($dataPolicy->issue_date) : $dataPolicy->issue_date,
						'OD_policy_start_date' => (!empty($this->date_formate))? date_format_date($dataPolicy->start_date) : $dataPolicy->start_date,
						'OD_policy_end_date' => (!empty($this->date_formate))? date_format_date($dataPolicy->end_date) : $dataPolicy->end_date,
						// 'TP_tenure' => $dataPolicy->policy_tenure,
						'TP_start_date' => '',
						'TP_end_date' => '',
						'policy_no' => !empty($dataPolicy->policy_number) ?  "'".$dataPolicy->policy_number : null,
						'Payment Type'=> !($payments->isEmpty())?get_payment_type($payments[0]->payment_type):null,
						'bank_name' => $bank_name,
						'account_number' => (!empty($account_no))? "'".$account_no:'',
						'Cheque_no' => $check_no,
						'Cheque_date' => (!empty($this->date_formate))? date_format_date($check_date) : $check_date,
						'amount' => $amount+$amount_2,
						'bank_name_2' => $bank_name_2,
						'account_number_2' => (!empty($account_no_2))? "'".$account_no_2 :'',
						'Cheque_no_2' => $check_no_2,
						'Cheque_date_2' => (!empty($this->date_formate))? date_format_date($check_date_2) : $check_date_2 ,
						'amount_2' => (!empty($amount_2))?$amount_2:'0',
						'Occupancies' => $dataPolicy->occupancies,
						'Outward_GeneratedDt' =>!empty($dataPolicy->outward)? $dataPolicy->outward->created_at->format('d/m/Y'):'',
						'Previous_Policy_No' => (!empty($Previous_Policy_No))?  "'".$Previous_Policy_No :'' ,
						'Previous_Policy_company' => $Previous_Policy_company,
						'Proposal_DOB' => '',
						'Proposal_Age' => '0',
						'Remark' => $dataPolicy->remark,
						'created_by' => !empty($dataPolicy->created_by)?$dataPolicy->created_by->first_name:'',
						'updated_at' => $dataPolicy->updated_at->format('d/m/Y'),
						'updated_by' => !empty($dataPolicy->updated_by)?$dataPolicy->updated_by->first_name:'',
						'updatede_by_date' => $dataPolicy->updated_at->format('d/m/Y'),
						'edited_by' => !empty($dataPolicy->edited_by)?(ucwords( $dataPolicy->edited_by->first_name .' '. $dataPolicy->edited_by->last_name )):'',
						'edited_at' => (!empty($dataPolicy->edited_at))? Carbon::parse($dataPolicy->edited_at)->format('d/m/Y') : '',
						'policy_cancel_reason' => $dataPolicy->policy_cancel_reason,
						'policy_cancel_remark' => $dataPolicy->policy_cancel_remark,
					];
            	}
        	}
    	}
       return collect($data);
    }

	public function headings(): array{
		return [
            'Business Year',
            'Business Month',
            'Inward No',
            'Entry Date',
            'RETINUE Branch',
            'FDO Code',
            'Agent Code',
			'Customer ID',
            'Prefix',
            'Customer Name',
            'Company Name',
            'Company Branch Name',
            'CODE Type',
            'IMD Code Name',
            'Sector',
            'Insurance Type',
            'Main Product',
            'Product Name',
            'Sub Product Name',
            'Product Type',
            'Policy Tenure',
            'Policy Type',
            'Registration No',
			'RTO Code',
			'RTO City',
            'Engine No',
            'Chasis No',
            'Make',
            'Model',
            'Variant',
            'CC / GVW',
            'Manufacturing Year',
            'Seating Capacity',
            'Fuel Type',
            'Total IDV / SUM INSURED',
            'Discount',
            'NCB',
            'OD',
            'ADD ON',
            'TOTAL OD',
            'TP',
			'CPA Cover',
            'Terrorism',
			'TOTAL TP PREMIUM / TERRORISM',
            'NET PREMIUM',
            'GST',
            'GST Value',
			'Stamp Duty',
            'GROSS PREMIUM',
            'Policy Issue Date',
            'OD Policy Start Date',
            'OD Policy End Date',
            // 'TP Tenure',
            'TP Start Date',
            'TP End Date',
            'Policy NO',
            'Payment Type',
            'Bank Name',
            'Account No',
            'Cheque No',
            'Cheque Date',
            'Amount',
            'Bank Name 2',
            'Account No 2',
            'ChequeNo 2',
            'Cheque Date 2',
            'Amount 2',
            'Occupancies',
            'Outward GeneratedDt',
            'Previous Policy No',
			'Previous Policy Company',
            'Proposal DOB',
            'Proposal Age',
            'Remark',
			'Receipting done by',
			'Receipting date',
			'Policy Updated By',
			'Policy Updated Date',
			'Policy Edited By',
			'Policy Edited Date',
			'Policy Cancel_Reason',
			'Cancellation remarks'
        ];
	}
	
	

}
