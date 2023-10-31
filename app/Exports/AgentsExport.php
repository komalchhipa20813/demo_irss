<?php

namespace App\Exports;

use Log;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\Agent;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AgentsExport implements FromQuery, WithHeadings, ShouldAutoSize, WithEvents, WithMapping
{

    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function query()
    {
        $agents = Agent::query()->where('status', 1)->with(['fdo','branch','documents']);
        return $agents;
    }

    public function map($agents): array
    {
        if($agents->gender == 0){
            $gender = 'Male';
        }elseif($agents->gender == 1){
            $gender = 'Female';
        }
        return [
            $agents->fdo->code,
            $agents->code,
            (!is_null($agents->branch)) ?  $agents->branch->name : '',
            $agents->email,
            $agents->prefix.' '.$agents->first_name.' '.$agents->last_name,
            $agents->phone,
            $agents->secondary_phone,
            $agents->office_address,
            $agents->residential_address,
            $gender,
            $agents->dob,
            $agents->anniversary_date,
            $agents->joining_date,
            (!empty($agents->account_numbe))?'~'.$agents->account_number:'',
            $agents->ifsc_code,
            $agents->adharcard_number,
            $agents->pancard_number
        ];
    }

    public function headings(): array
    {
        return [
            'Fdo Code',
            'Code',
            'Home irss Branch Name',
            'Email',
            'Name',
            'Phone',
            'Secondary Phone',
            'Office Address',
            'Residential Address',
            'Gender',
            'Date of Birth',
            'Anniversary Date',
            'Joining Date',
            'Account Number',
            'IFSC Code',
            'Adhar Card No',
            'Pan Card No'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            },
        ];
    }
}

