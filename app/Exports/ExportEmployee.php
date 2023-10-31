<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportEmployee implements FromQuery, WithHeadings, ShouldAutoSize, WithEvents, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        $employees = User::query()->where('role_id', 2)->where('status', 1)->with(['role','branch','department','designation']);
        return $employees;
    }

    public function map($employees): array
    {
        if($employees->gender == 0){
            $gender = 'Male';
        }elseif($employees->gender == 1){
            $gender = 'Female';
        }
        return [
            $employees->role->title,
            $employees->branch->name,
            $employees->department->name,
            $employees->designation->name,
            $employees->email,
            $employees->prefix.' '.$employees->first_name.' '.$employees->last_name,
            $employees->phone,
            $employees->address,
            $gender,
            $employees->dob,
            $employees->anniversary_date,
            $employees->joining_date,
            $employees->salary,
            $employees->account_number,
            $employees->ifsc_code,
        ];
    }

    public function headings(): array
    {
        return [
            'Role Name',
            'Branch Name',
            'Department Name',
            'Designation Name',
            'Email',
            'Full Name',
            'Phone',
            'Address',
            'Gender',
            'Date of Birth',
            'Anniversary Date',
            'Joining Date',
            'Salary',
            'Account Number',
            'IFSC Code'
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
