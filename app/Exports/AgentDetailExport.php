<?php

namespace App\Exports;

use App\Models\Agent;
use App\Models\fdo;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class AgentDetailExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping,WithEvents, WithCustomStartCell
{
   protected $fdo_id, $code, $account_number,$bank_name, $name, $dob ,$ifsc_code,$pan_no,$created_on,$status,$start_date,$end_date;
	function __construct($fdo_id = '', $code = '', $account_number = '',$bank_name='', $name = '', $dob = '' ,$ifsc_code = '', $pan_no = '',$created_on = '',$status = '',$start_date='',$end_date = '') {
		$this->fdo_id = $fdo_id;
		$this->code = $code;
		$this->account_number = $account_number;
		$this->bank_name=$bank_name;
		$this->name = $name;
		$this->dob = $dob;
		$this->ifsc_code=$ifsc_code;
		$this->pan_no=$pan_no;
		$this->created_on=$created_on;
		$this->status=$status;
		$this->start_date=$start_date;
		$this->end_date=$end_date;
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


				$cellRange = 'A1:W1'; // All headers
				$event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
				$event->sheet->getDelegate()->getStyle('A1:W1')
					->getFont()
					->setBold(true);

				$styleArray = [
					'alignment' => [
						'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
					],
				];
			},
		];
	}

	public function headings(): array{
		$heading=[];
		if($this->code != '')
		{
			array_push($heading,'Code');
		}
		if($this->name != '')
		{
			array_push($heading,'Name');
		}
		if($this->account_number != '')
		{
			array_push($heading,'Account Number');
		}
		if($this->bank_name != '')
		{
			array_push($heading,'Bank Name');
		}
		if($this->dob != '')
		{
			array_push($heading,'Date Of Birth');
		}
		if($this->ifsc_code != '')
		{
			array_push($heading,'IFSC Code');
		}
		if($this->pan_no != '')
		{
			array_push($heading,'Pan Card Number');
		}

		if($this->created_on != '')
		{
			array_push($heading,'created on Date');
		}
		
		return $heading;
	}

	public function query() {

		$data = Agent::query();
		if ($this->fdo_id != '') {
			$data->where('fdo_id', $this->fdo_id);
		}
		if ($this->status != '') {
			$data->where('status', $this->status);
		}

		if ($this->start_date != '' && $this->end_date != '') {
                $from_date = Carbon::parse($this->start_date)->format('Y-m-d 00:00:00');
                $to_date = Carbon::parse($this->end_date)->format('Y-m-d 23:59:59');
                $data->whereBetween('created_at', [$from_date, $to_date]);
          }
		return $data;

	}
	private $sr_no = 1;
	public function map($bulk): array
	{
		$value_data=[];
		if($this->code != '')
		{
			array_push($value_data,$bulk->code);
		}
		if($this->name != '')
		{
			$name = $bulk->prefix .''. $bulk->first_name .' '. $bulk->middle_name .' '. $bulk->last_name;
			array_push($value_data,$name);
		}
		if($this->account_number != '')
		{
			$account_number_value=(!empty($bulk->account_number))? "'".$bulk->account_number:'';
			array_push($value_data, $account_number_value);
		}
		if($this->bank_name != '')
		{
			array_push($value_data, $bulk->bank_name);
		}
		if($this->dob != '')
		{
			array_push($value_data,$bulk->dob);
		}
		if($this->ifsc_code != '')
		{
			array_push($value_data,	$bulk->ifsc_code);
		}
		if($this->pan_no != '')
		{
			array_push($value_data,$bulk->pancard_number);
		}

		if($this->created_on != '')
		{
			array_push($value_data,date('d-m-Y', strtotime($bulk->created_at)));
		}
		return $value_data;
	}


}
