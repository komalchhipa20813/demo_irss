<?php

namespace App\Exports;

use App\Models\SmePolicy;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportUpdateSMEPolicy implements FromQuery, WithHeadings, ShouldAutoSize, WithEvents, WithMapping
{
    protected $agent,$name,$branch,$company,$cheque_no,$inward_no,$policy_no,$product,$policy_start_date,$policy_end_date,$start_date,$end_date;
    function __construct($data) {
        $this->agent=$data['agent'];
        $this->name=$data['name'];
        $this->branch=$data['branch'];
        $this->company=$data['company'];
        $this->cheque_no=$data['cheque_no'];
        $this->inward_no=$data['inward_no'];
        $this->policy_no=$data['policy_no'];
        $this->product=$data['product'];
        $this->policy_start_date=$data['policy_start_date'];
        $this->policy_end_date=$data['policy_end_date'];
        $this->start_date=$data['start_date'];
        $this->end_date=$data['end_date'];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        $request=[];
        $getPolicy =  SmePolicy::where('status','=',1)->where('policy_number',null)->with('customer','agent','previous_company','renewal_previous_company','company');
        if($this->agent != ''){
                $getPolicy->where('agent_id', $this->agent);
            }
            if( $this->name != ''){
                $getPolicy->whereHas('customer', function ($query) use ($request) {
                    $query->where('first_name', 'like', '%'. $this->name.'%')
                    ->orWhere('middle_name', 'like', '%'. $this->name.'%')
                    ->orWhere('last_name', 'like', '%'. $this->name.'%');
            });
            }
            if($this->branch != ''){
                $getPolicy->where('irss_branch_id',$this->branch);
            }
            if($this->company != ''){
                $getPolicy->whereHas('company', function ($queryData) use ($request) {
                    $queryData->where('id',$this->company);
                });
            }
            if($this->cheque_no != ''){
                $getPolicy->whereHas('payments', function ($query) use ($request) {
                    $query->where('payment_type', 2)->where('number', $this->cheque_no);
                });
            }
            if($this->inward_no != ''){
                $getPolicy->where('inward_no',$this->inward_no);
            }
            if($this->policy_no != ''){
                $getPolicy->where('policy_number',$this->policy_no);
            }
            if( $this->product != ''){
                $getPolicy->whereHas('product', function ($query) use ($request) {
                    $query->where('id',  $this->product);
                });
            }

            if ($this->policy_start_date != '' && $this->policy_end_date != '') {
                $getPolicy->where('start_date', '<=', $this->policy_start_date)->where('end_date', '>=', $this->policy_end_date);
            }else{
                if($this->policy_start_date != ''){
                    $getPolicy->where('start_date',$this->policy_start_date);
                }
                if($this->policy_end_date != ''){
                    $getPolicy->where('end_date',$this->policy_end_date);
                }
            }

            if ($this->start_date != '' && $this->end_date != '') {

                $start_date = Carbon::parse($this->start_date)->format('Y-m-d 00:00:00');
                $to_date = Carbon::parse($this->end_date)->format('Y-m-d 23:59:59');
                $getPolicy->whereBetween('created_at', [$start_date, $to_date]);
            }else{
                if($this->start_date != ''){
                    $start_date = Carbon::parse($this->start_date)->format('Y-m-d 00:00:00');
                    $getPolicy->where('created_at',$start_date);
                }
                if($this->end_date != ''){
                    $to_date = Carbon::parse($this->end_date)->format('Y-m-d 23:59:59');
                    $getPolicy->where('created_at',$to_date);
                }
            }
        return $getPolicy;
    }

    public function map($getPolicy): array
    {

        $Previous_Policy_No=!empty($getPolicy->previous_policy_number)?'*'.$getPolicy->previous_policy_number:(!empty($getPolicy->renewal_previous_policy_number)?'*'.$getPolicy->renewal_previous_policy_number:'');
		$Previous_Policy_company=!empty($getPolicy->previous_company)?$getPolicy->previous_company->name:(!empty($getPolicy->renewal_previous_company)?$getPolicy->renewal_previous_company->name:'');
        return [
        	isset($getPolicy->created_at) ? $getPolicy->created_at->format('d-m-Y'): '-',
            $getPolicy->inward_no,
            $getPolicy->customer->prefix.' '.$getPolicy->customer->first_name.' '.$getPolicy->customer->middle_name.' '.$getPolicy->customer->last_name,
            $getPolicy->agent->code,
            $getPolicy->company->name,
            'SME',
            !empty($getPolicy->policy_number)?'*'.$getPolicy->policy_number:'',
            $getPolicy->start_date,
            $getPolicy->end_date,
            $Previous_Policy_No,
            $Previous_Policy_company,
            $getPolicy->total_premium,
        ];
    }

    public function headings(): array
    {
        return [
        	'Entry Date',
            'Inward Number',
            'Customer Name',
            'Agent Code',
            'Company Name',
            'Main Product',
            'Policy Number',
            'Start Date',
            'End Date',
            'Previous Policy No',
			'Previous Policy Company',
            'Total Premium',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:L1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            },
        ];
    }
}
