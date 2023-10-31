<?php

namespace App\Exports;

use App\Models\MotorPolicy;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CancelMotorPolicyExport implements FromQuery, WithHeadings, ShouldAutoSize, WithEvents, WithMapping
{
    protected $agent, $name, $branch, $company, $cheque_no, $inward_no, $policy_no, $engine_no, $chasis_no, $registration_no, $product, $policy_start_date, $policy_end_date, $from_date, $end_date;
    function __construct($data)
    {
        $this->agent = $data['agent'];
        $this->name = $data['name'];
        $this->branch = $data['branch'];
        $this->company = $data['company'];
        $this->cheque_no = $data['cheque_no'];
        $this->inward_no = $data['inward_no'];
        $this->policy_no = $data['policy_no'];
        $this->engine_no = $data['engine_no'];
        $this->chasis_no = $data['chasis_no'];
        $this->registration_no = $data['registration_no'];
        $this->product = $data['product'];
        $this->policy_start_date = $data['policy_start_date'];
        $this->policy_end_date = $data['policy_end_date'];
        $this->from_date = $data['from_date'];
        $this->end_date = $data['end_date'];
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        $request = [];
        $motorPolicy = MotorPolicy::query()->where('status', 3)->with(['product', 'sub_product', 'customer', 'branch', 'company', 'companyBranch', 'branch_imd_name', 'payments', 'motor_policy_vehicle']);
        if ($this->agent != '') {
            $motorPolicy->where('agent_id', $this->agent);
        }
        if ($this->name != '') {
            $motorPolicy->whereHas('customer', function ($query) use ($request) {
                $query->where('first_name', 'like', '%' . $this->name . '%')
                    ->orWhere('middle_name', 'like', '%' . $this->name . '%')
                    ->orWhere('last_name', 'like', '%' . $this->name . '%');
            });
        }
        if ($this->branch != '') {
            $motorPolicy->where('irss_branch_id', $this->branch);
        }
        if ($this->company != '') {
            $motorPolicy->whereHas('company', function ($queryData) use ($request) {
                $queryData->where('id', $this->company);
            });
        }
        if ($this->cheque_no != '') {
            $motorPolicy->whereHas('payments', function ($query) use ($request) {
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
            $motorPolicy->whereHas('product', function ($query) use ($request) {
                $query->where('id',  $this->product);
            });
        }
        if ($this->engine_no != '') {
            $motorPolicy->whereHas('motor_policy_vehicle', function ($query) use ($request) {
                $query->where('engine_no', $this->engine_no);
            });
        }
        if ($this->chasis_no != '') {
            $motorPolicy->whereHas('motor_policy_vehicle', function ($query) use ($request) {
                $query->where('chasiss_no', $this->chasis_no);
            });
        }
        if ($this->registration_no != '') {
            $motorPolicy->whereHas('motor_policy_vehicle', function ($query) use ($request) {
                $query->where('registration_no', realVehicleNo($this->registration_no));
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

        return $motorPolicy;
    }

    public function map($getPolicy): array
    {
        $payments = $getPolicy->payments;
        $bank_name = '';
        $account_no = '';
        $check_no = '';
        $check_date = '';
        $amount = 0;
        $bank_name_2 = '';
        $account_no_2 = '';
        $check_no_2 = '';
        $check_date_2 = '';
        $amount_2 = 0;

        foreach ($payments as $key => $payment) {
            $bank_name = (!empty($payment->bank_id)) ? $payment->bank->name : '';
            $account_no = $payment->account_number;
            $check_no = $payment->number;
            $check_date = $payment->payment_date;
            $amount = $payment->amount;
            if ($key == 1) {
                $bank_name_2 = $payment->bank->name;
                $account_no_2 = $payment->account_number;
                $check_no_2 = $payment->number;
                $check_date_2 = $payment->payment_date;
                $amount_2 = $payment->amount;
            }
        }

        return [
            isset($getPolicy->created_at) ? $getPolicy->created_at->format('d-m-Y') : '-',
            (!empty($getPolicy->policy_number))? "'".$getPolicy->policy_number:'',
            $getPolicy->branch->name,
            $getPolicy->inward_no,
            $getPolicy->customer->customer_code,
            $getPolicy->customer->prefix . ' ' . $getPolicy->customer->first_name . ' ' . $getPolicy->customer->middle_name . ' ' . $getPolicy->customer->last_name,
            $getPolicy->company->name,
            !empty($getPolicy->companyBranch->name) ? $getPolicy->companyBranch->name : '',
            (!empty($getPolicy->branch_imd_id)) ? $getPolicy->branch_imd_name->name : '',
            'MOTOR',
            $getPolicy->product->name,
            (!empty($getPolicy->sub_product_id)) ? $getPolicy->sub_product->name : '',
            $getPolicy->policy_type,
            !empty($getPolicy->motor_policy_vehicle) ? vehicleNO($getPolicy->motor_policy_vehicle->registration_no) : '',
            $getPolicy->start_date,
            '-',
            $check_no,
            $check_date,
            $amount,
            (!empty($account_no))? "'".$account_no:'',
            $check_no_2,
            $check_date_2,
            $amount_2,
            (!empty($account_no_2))? "'".$account_no_2:'',
            $getPolicy->policy_cancel_reason,
            $getPolicy->policy_cancel_remark,
        ];
    }

    public function headings(): array
    {
        return [
            'Entry Date',
            'Policy Number',
            'Branch Name',
            'Inward Number',
            'Customer Code',
            'Customer Name',
            'Company Name',
            'Company Branch Name',
            'IMD Code & Name',
            'Main Product',
            'Product',
            'Sub Product',
            'Policy Type',
            'Registration No',
            'Policy Date',
            'Cash',
            'Cheque No.',
            'Cheque Date',
            'Amount',
            'Account No.',
            'Cheque No.2',
            'Cheque Date2',
            'Amount2',
            'Account No.2',
            'Policy Cancel Reason',
            'Policy Cancel Remark',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:Z1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            },
        ];
    }
}
