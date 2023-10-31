<?php

namespace App\Exports;

use App\Models\{MotorPolicy, HealthPolicy, SmePolicy};
use Carbon\Carbon;
use DateTime;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportUpdateAllPolicy implements FromCollection, ShouldAutoSize, WithEvents, WithHeadings, WithCustomStartCell
{
	protected $agent, $name, $branch, $company, $cheque_no, $inward_no, $policy_no, $product, $policy_start_date, $policy_end_date, $from_date, $end_date;
	function __construct($data)
	{
		$this->agent = $data['agent'];
		$this->name = $data['name'];
		$this->branch = $data['branch'];
		$this->company = $data['company'];
		$this->cheque_no = $data['cheque_no'];
		$this->inward_no = $data['inward_no'];
		$this->policy_no = $data['policy_no'];
		$this->product = $data['product'];
		$this->policy_start_date = $data['policy_start_date'];
		$this->policy_end_date = $data['policy_end_date'];
		$this->from_date = $data['from_date'];
		$this->end_date = $data['end_date'];
	}

	/**
	 * @return \Illuminate\Support\Collection
	 */
	public function startCell(): string
	{
		return 'A1';
	}

	public function registerEvents(): array
	{
		return [
			AfterSheet::class    => function (AfterSheet $event) {
				$cellRange = 'A1:T1'; // All headers
				$event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
				$event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
			},
		];
	}
	public function collection()
	{
		$data = [];

		$motorPolicy = MotorPolicy::where('status', '=', 1)->where('policy_number', null)->with('customer', 'company', 'agent', 'previous_company', 'renewal_previous_company', 'product', 'sub_product', 'motor_policy_vehicle_only', 'payments');

		if ($this->agent != '') {
			$motorPolicy->where('agent_id', $this->agent);
		}
		if ($this->name != '') {
			$motorPolicy->whereHas('customer', function ($query) {
				$query->where('first_name', 'like', '%' . $this->name . '%')
					->orWhere('middle_name', 'like', '%' . $this->name . '%')
					->orWhere('last_name', 'like', '%' . $this->name . '%');
			});
		}
		if ($this->branch != '') {
			$motorPolicy->where('irss_branch_id', $this->branch);
		}
		if ($this->company != '') {
			$motorPolicy->whereHas('company', function ($queryData) {
				$queryData->where('id', $this->company);
			});
		}
		if ($this->cheque_no != '') {
			$motorPolicy->whereHas('payments', function ($query) {
				$query->where('payment_type', 2)->where('number', $this->cheque_no);
			});
		}
		if ($this->inward_no != '') {
			$motorPolicy->where('inward_no', $this->inward_no);
		}
		if ($this->policy_no != '') {
			$motorPolicy->where('policy_number', $this->policy_no);
		}
		if ($this->product != '') {
			$motorPolicy->whereHas('product', function ($query) {
				$query->where('id',  $this->product);
			});
		}


		if ($this->policy_start_date != '' && $this->policy_end_date != '') {
			$motorPolicy->where('start_date', '<=', $this->policy_start_date)->where('end_date', '>=', $this->policy_end_date);
		} else {
			if ($this->policy_start_date != '') {
				$motorPolicy->where('start_date', $this->policy_start_date);
			}
			if ($this->policy_end_date != '') {
				$motorPolicy->where('end_date', $this->policy_end_date);
			}
		}

		if ($this->from_date != '' && $this->end_date != '') {

			$from_date = Carbon::parse($this->from_date)->format('Y-m-d 00:00:00');
			$to_date = Carbon::parse($this->end_date)->format('Y-m-d 23:59:59');
			$motorPolicy->whereBetween('created_at', [$from_date, $to_date]);
		} else {
			if ($this->from_date != '') {
				$from_date = Carbon::parse($this->from_date)->format('Y-m-d 00:00:00');
				$motorPolicy->where('created_at', $from_date);
			}
			if ($this->end_date != '') {
				$to_date = Carbon::parse($this->end_date)->format('Y-m-d 23:59:59');
				$motorPolicy->where('created_at', $to_date);
			}
		}
		$motorPolicys = $motorPolicy->latest()->get();

		if ($motorPolicys->isNotEmpty()) {
			foreach ($motorPolicys as $key => $dataPolicy) {
				$Previous_Policy_No = !empty($dataPolicy->previous_policy_number) ? '*' . $dataPolicy->previous_policy_number : (!empty($dataPolicy->renewal_previous_policy_number) ? '*' . $dataPolicy->renewal_previous_policy_number : '');
				$Previous_Policy_company = !empty($dataPolicy->previous_company) ? $dataPolicy->previous_company->name : (!empty($dataPolicy->renewal_previous_company) ? $dataPolicy->renewal_previous_company->name : '');
				$data[] = [
					isset($dataPolicy->created_at) ? $dataPolicy->created_at->format('d-m-Y') : '-',
					$dataPolicy->inward_no,
					$dataPolicy->customer->prefix . ' ' . $dataPolicy->customer->first_name . ' ' . $dataPolicy->customer->middle_name . ' ' . $dataPolicy->customer->last_name,
					$dataPolicy->agent->code,
					$dataPolicy->company->name,
					$dataPolicy->policy_type,
					'MOTOR',
					!empty($dataPolicy->product)?$dataPolicy->product->name:'',
					!empty($dataPolicy->sub_product)?$dataPolicy->sub_product->name:'',
					!empty($dataPolicy->motor_policy_vehicle_only)?vehicleNO($dataPolicy->motor_policy_vehicle_only->registration_no):'',
					$Previous_Policy_No,
					$Previous_Policy_company,
					$dataPolicy->total_premium,
					config('constants.health_policy_payments.payment_type.' . $dataPolicy->payments[0]->payment_type),
					!empty($dataPolicy->payments[0]->bank)?$dataPolicy->payments[0]->bank->name:'',
					$dataPolicy->payments[0]->number,
					$dataPolicy->payments[0]->payment_date,
				];
			}
		}


		//Health Policy

		$health_query = HealthPolicy::query()->where('status', '=', 1)->where('policy_number', null)->with('customer', 'company', 'agent', 'previous_company', 'renewal_previous_company', 'product', 'sub_product', 'payments');
		if ($this->from_date != '' && $this->end_date != '') {
			$from_date = Carbon::createFromFormat('d-m-Y', $this->from_date)->format('Y-m-d 00:00:00');
			$to_date = Carbon::createFromFormat('d-m-Y', $this->end_date)->format('Y-m-d 23:59:59');
			$health_query->whereBetween('created_at', [$from_date, $to_date]);
		}
		if ($this->policy_start_date != '' && $this->policy_end_date != '') {
			$from_date = Carbon::parse($this->policy_start_date)->format('Y-m-d 00:00:00');
			$to_date = Carbon::parse($this->policy_end_date)->format('Y-m-d 23:59:59');
			$health_query->whereBetween('end_date', [$from_date, $to_date]);
		}
		if ($this->branch != '') {
			$health_query->where('irss_branch_id', $this->branch);
		}
		if ($this->company != '') {
			$health_query->where('company_id', $this->company);
		}

		if ($this->agent != '') {
			$health_query->where('agent_id', $this->agent);
		}

		$healthPolicys = $health_query->latest()->get();
		if ($healthPolicys->isNotEmpty()) {
			foreach ($healthPolicys as $key => $dataPolicy) {
				$Previous_Policy_No = !empty($dataPolicy->previous_policy_number) ? '*' . $dataPolicy->previous_policy_number : (!empty($dataPolicy->renewal_previous_policy_number) ? '*' . $dataPolicy->renewal_previous_policy_number : '');
				$Previous_Policy_company = !empty($dataPolicy->previous_company) ? $dataPolicy->previous_company->name : (!empty($dataPolicy->renewal_previous_company) ? $dataPolicy->renewal_previous_company->name : '');
				$data[] = [
					isset($dataPolicy->created_at) ? $dataPolicy->created_at->format('d-m-Y') : '-',
					$dataPolicy->inward_no,
					$dataPolicy->customer->prefix . ' ' . $dataPolicy->customer->first_name . ' ' . $dataPolicy->customer->middle_name . ' ' . $dataPolicy->customer->last_name,
					$dataPolicy->agent->code,
					$dataPolicy->company->name,
					$dataPolicy->policy_type,
					'HEALTH',
					!empty($dataPolicy->product)?$dataPolicy->product->name:'',
					!empty($dataPolicy->sub_product)?$dataPolicy->sub_product->name:'',
					'-',
					$Previous_Policy_No,
					$Previous_Policy_company,
					$dataPolicy->total_premium,
					config('constants.health_policy_payments.payment_type.' . $dataPolicy->payments[0]->payment_type),
					!empty($dataPolicy->payments[0]->bank)?$dataPolicy->payments[0]->bank->name:'',
					$dataPolicy->payments[0]->number,
					$dataPolicy->payments[0]->payment_date,
				];
			}
		}

		//SME Policy


		$sme_query = SmePolicy::where('status', '=', 1)->where('policy_number', null)->with('customer', 'company', 'agent', 'previous_company', 'renewal_previous_company', 'product', 'sub_product', 'payments');

		if ($this->from_date != '' && $this->end_date != '') {
			$from_date = Carbon::createFromFormat('d-m-Y', $this->from_date)->format('Y-m-d 00:00:00');
			$to_date = Carbon::createFromFormat('d-m-Y', $this->end_date)->format('Y-m-d 23:59:59');
			$sme_query->whereBetween('created_at', [$from_date, $to_date]);
		}
		if ($this->policy_start_date != '' && $this->policy_end_date != '') {
			$from_date = Carbon::parse($this->policy_start_date)->format('Y-m-d 00:00:00');
			$to_date = Carbon::parse($this->policy_end_date)->format('Y-m-d 23:59:59');
			$sme_query->whereBetween('end_date', [$from_date, $to_date]);
		}
		if ($this->branch != '') {
			$sme_query->where('irss_branch_id', $this->branch);
		}
		if ($this->company != '') {
			$sme_query->where('company_id', $this->company);
		}

		if ($this->agent != '') {
			$sme_query->where('agent_id', $this->agent);
		}

		$smePolicys = $sme_query->latest()->get();

		if ($smePolicys->isNotEmpty()) {
			foreach ($smePolicys as $key => $dataPolicy) {
				$Previous_Policy_No = !empty($dataPolicy->previous_policy_number) ? '*' . $dataPolicy->previous_policy_number : (!empty($dataPolicy->renewal_previous_policy_number) ? '*' . $dataPolicy->renewal_previous_policy_number : '');
				$Previous_Policy_company = !empty($dataPolicy->previous_company) ? $dataPolicy->previous_company->name : (!empty($dataPolicy->renewal_previous_company) ? $dataPolicy->renewal_previous_company->name : '');
				$data[] = [
					isset($dataPolicy->created_at) ? $dataPolicy->created_at->format('d-m-Y') : '-',
					$dataPolicy->inward_no,
					$dataPolicy->customer->prefix . ' ' . $dataPolicy->customer->first_name . ' ' . $dataPolicy->customer->middle_name . ' ' . $dataPolicy->customer->last_name,
					$dataPolicy->agent->code,
					$dataPolicy->company->name,
					$dataPolicy->policy_type,
					'SME',
					!empty($dataPolicy->product)?$dataPolicy->product->name:'',
					!empty($dataPolicy->sub_product)?$dataPolicy->sub_product->name:'',
					'-',
					$Previous_Policy_No,
					$Previous_Policy_company,
					$dataPolicy->total_premium,
					config('constants.health_policy_payments.payment_type.' . $dataPolicy->payments[0]->payment_type),
					!empty($dataPolicy->payments[0]->bank)?$dataPolicy->payments[0]->bank->name:'',
					$dataPolicy->payments[0]->number,
					$dataPolicy->payments[0]->payment_date,
				];
			}
		}
		return collect($data);
	}

	public function headings(): array
	{
		return [
			'Entry Date',
			'Inward Number',
			'Customer Name',
			'Agent Code',
			'Company Name',
			'Policy Type',
			'Main Product',
			'Product Name',
			'Sub Product Name',
			'Registration No',
			'Previous Policy No',
			'Previous Policy Company',
			'Total Premium',
			'Payment Type',
			'Bank Name',
			'Cheque No',
			'Cheque Date',
		];
	}
}
