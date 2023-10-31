<?php

namespace App\Exports;

use App\Models\RaiseQuery;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportSolvedQuery implements FromQuery, WithHeadings, ShouldAutoSize, WithEvents, WithMapping
{

    protected $agent,$company,$inward_no,$paased_days,$product, $from_date ,$end_date;
    function __construct($data) {
        $this->agent=$data['agent'];
        $this->company=$data['company'];
        $this->product=$data['product'];
        $this->inward_no=$data['inward_no'];
        $this->paased_days=$data['paased_days'];
        $this->from_date=$data['from_date'];
        $this->end_date=$data['end_date'];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        $request=[];
        $raise_queries = RaiseQuery::where('status',2)->with('motor_policy','health_policy','sme_policy','query_closed_by');
            if($this->agent != ''){
                $raise_queries->where(function( $subquery ) use ($request){
                    $subquery->whereHas('motor_policy', function ( $query ) use ($request){
                        $query->WhereHas('agent_only', function ($aquery) use ($request) {
                                    $aquery->where('code', $this->agent);
                                });
                    } );
                    $subquery->orWhereHas('health_policy', function ($query) use ($request) {
                            $query->WhereHas('agent_only', function ($hquery) use ($request) {
                                $hquery->where('code', $this->agent);
                            });
                     });
                    $subquery->orWhereHas('sme_policy', function ($query) use ($request) {
                            $query->WhereHas('agent', function ($squery) use ($request) {
                                $squery->where('code', $this->agent);
                            });
                        });
                });
            }
            
            if($this->company != ''){
                $raise_queries->where(function( $subquery ) use ($request){
                    $subquery->WhereHas('motor_policy', function ($query) use ($request) {
                        $query->where('company_id', $this->company);
                     });
                    $subquery->orWhereHas('health_policy', function ($query) use ($request) {
                        $query->where('company_id', $this->company);
                    });
                    $subquery->orWhereHas('sme_policy', function ($query) use ($request) {
                        $query->where('company_id', $this->company);
                    });
                });
            }
            
            if($this->inward_no != ''){
                $raise_queries->where(function( $subquery ) use ($request){
                    $subquery->WhereHas('motor_policy', function ($query) use ($request) {
                        $query->where('inward_no', $this->inward_no);
                    });
                    $subquery->orWhereHas('health_policy', function ($query) use ($request) {
                        $query->where('inward_no', $this->inward_no);
                    });
                    $subquery->orWhereHas('sme_policy', function ($query) use ($request) {
                        $query->where('inward_no', $this->inward_no);
                    });
                });
            }
            
            if( $this->product != '' && $this->product != '0'){
                $raise_queries->where('policy_type',$request->product);
            }

            if ($this->paased_days != '') {
                $raise_queries->where('tat', $this->paased_days);
             }


            if ($this->from_date != '' && $this->end_date != '') {

                $from_date = Carbon::parse($this->from_date)->format('Y-m-d 00:00:00');
                $to_date = Carbon::parse($this->end_date)->format('Y-m-d 23:59:59');
                $raise_queries->whereBetween('raised_on', [$from_date, $to_date]);
            }else{
                if($this->from_date != ''){
                    $from_date = Carbon::parse($this->from_date)->format('Y-m-d 00:00:00');
                    $raise_queries->where('raised_on',$from_date);
                }
                if($this->end_date != ''){
                    $to_date = Carbon::parse($this->end_date)->format('Y-m-d 23:59:59');
                    $raise_queries->where('raised_on',$to_date);
                }
            }

        return $raise_queries;
    }

    public function map($raise_query): array
    {
        $agent_code='';
        $inward_no='';
        $sub_product='';
        $registration_no='';
        $company='';
        $product='';

        if(!empty($raise_query->motor_policy))
        {
            $agent_code=$raise_query->motor_policy->agent_only->code;
            $inward_no=$raise_query->motor_policy->inward_no;
            $sub_product=(!empty($raise_query->motor_policy->sub_product_id))?$raise_query->motor_policy->sub_product->name :'';
            $registration_no=$raise_query->motor_policy->motor_policy_vehicle_only->registration_no;
            $company=$raise_query->motor_policy->company->name;
            $product='MOTOR';
        }
        elseif(!empty($raise_query->health_policy))
        {
            $agent_code=$raise_query->health_policy->agent_only->code;
            $inward_no=$raise_query->health_policy->inward_no;
            $sub_product=(!empty($raise_query->health_policy->sub_product_id))?$raise_query->health_policy->sub_product->name : '';
            $company=$raise_query->health_policy->company->name;
            $product='HEALTH';
        }
        elseif(!empty($raise_query->sme_policy))
        {
            $agent_code=$raise_query->sme_policy->agent->code;
            $inward_no=$raise_query->sme_policy->inward_no;
            $sub_product= (!empty($raise_query->sme_policy->sub_product_id))?$raise_query->sme_policy->sub_product->name : '';
            $company=$raise_query->sme_policy->company->name;
            $product='SME';
        }

        

        return [
            $raise_query->ticket_no,
            $agent_code,
            $inward_no,
            $product,
            $sub_product,
            $registration_no,
            $company,
            ucwords($raise_query->details),
            Carbon::parse($raise_query->raised_on)->format('d-m-Y'),
            $raise_query->tat,
            ucwords($raise_query->remark),
            (!empty($raise_query->closed_by)) ? ucwords((!empty($raise_query->query_closed_by->prefix)) ?$raise_query->query_closed_by->prefix.'.':'' .$raise_query->query_closed_by->first_name .' '.$raise_query->query_closed_by->middle_name .' '. $raise_query->query_closed_by->last_name) : '',
            Carbon::parse($raise_query->closed_date)->format('d-m-Y'),

        ];
    }

    public function headings(): array
    {
        return [
            'Ticket Number',
            'Agent Code',
            'Inward Number',
            'Main Product',
            'Sub Product',
            'Registration No.',
            'Company Name',
            'Query Details',
            'Raise On Date',
            'TAT',
            'Remark',
            'Close By',
            'Closed Date',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A1:BX1048576')
				->getAlignment()
				->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            },
        ];
    }
}
