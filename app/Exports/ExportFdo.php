<?php

namespace App\Exports;

use Log;
use App\Models\Fdo;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportFdo implements FromQuery, WithHeadings, ShouldAutoSize, WithEvents, WithMapping
{

    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function query()
    {
        $Fdos = Fdo::query()->where('status', 1)->with(['branch','business_category']);
        return $Fdos;
    }

    public function map($Fdos): array
    {
        if($Fdos->gender == 0){
            $gender = 'Male';
        }elseif($Fdos->gender == 1){
            $gender = 'Female';
        }
        return [
            $Fdos->code,
            (!is_null($Fdos->branch)) ?  $Fdos->branch->name : '',
            (!is_null($Fdos->business_category)) ?  $Fdos->business_category->name : '',
            $Fdos->email,
            $Fdos->prefix.' '.$Fdos->first_name.' '.$Fdos->last_name,
            $Fdos->phone,
            $Fdos->secondary_phone,
            $Fdos->office_address,
            $Fdos->residential_address,
            $gender,
            $Fdos->dob,
            $Fdos->anniversary_date,
            $Fdos->joining_date,
            $Fdos->effective_from,
            $Fdos->account_number,
            $Fdos->ifsc_code,
            $Fdos->adharcard_number,
            $Fdos->pancard_number
        ];
    }

    public function headings(): array
    {
        return [
            'Code',
            'Home irss Branch Name',
            'Business Category',
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
            'Effective From',
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

