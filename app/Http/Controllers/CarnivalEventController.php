<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\HealthPolicy;
use App\Models\MotorPolicy;
use App\Models\SmePolicy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarnivalEventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $start=Carbon::parse('2022-12-19')->format('Y-m-d 00:00:00');
        // $end = Carbon::parse('2022-12-21')->format('Y-m-d 23:59:59');
        // // $data['companies']=DB::table('companies')
        // //     ->leftjoin('health_policies', 'companies.id', '=', 'health_policies.company_id')
        // //     ->leftjoin('motor_policies', 'companies.id', '=', 'motor_policies.company_id')
        // //     ->leftjoin('sme_policies', 'companies.id', '=', 'sme_policies.company_id')
        // //     ->whereBetween('health_policies.created_at', [$start, $end])
        // //     ->orwherebetween('motor_policies.created_at', [$start, $end])
        // //     ->orwherebetween('sme_policies.created_at', [$start, $end])
        // //     ->select('companies.id', 'name',
        // //         DB::raw('count(DISTINCT(health_policies.id)) + count(DISTINCT(motor_policies.id)) + count(DISTINCT(sme_policies.id))  as nop'),
        // //         DB::raw('(CASE WHEN sum(DISTINCT(health_policies.od))  IS NOT NULL THEN sum(DISTINCT(health_policies.od))  ELSE 0 END) 
        // //         + (CASE WHEN sum(DISTINCT(motor_policies.od))  IS NOT NULL THEN sum(DISTINCT(motor_policies.od))  ELSE 0 END)+ (CASE WHEN sum(DISTINCT(motor_policies.addonpremium))  IS NOT NULL THEN sum(DISTINCT(motor_policies.addonpremium))  ELSE 0 END) + (CASE WHEN sum(DISTINCT(motor_policies.tp))  IS NOT NULL THEN sum(DISTINCT(motor_policies.tp))  ELSE 0 END) + (CASE WHEN sum(DISTINCT(motor_policies.pay_to_owner))  IS NOT NULL THEN sum(DISTINCT(motor_policies.pay_to_owner))  ELSE 0 END)
        // //         + (CASE WHEN sum(DISTINCT(sme_policies.terrorism_premium))  IS NOT NULL THEN sum(DISTINCT(sme_policies.terrorism_premium))  ELSE 0 END) + (CASE WHEN sum(DISTINCT(sme_policies.od))  IS NOT NULL THEN sum(DISTINCT(sme_policies.od))  ELSE 0 END) as total')
        // //     )
        // //     ->groupBy('companies.id')
        // //     ->get()
        // //     ->sortByDesc('total')
        // //     ->limit(10)
        // //     ->toArray();
        // $data['policy_wise']=[
        //     'health'=>HealthPolicy::whereBetween('health_policies.created_at', [$start, $end])->select(
        //         DB::raw('(CASE WHEN sum(DISTINCT(od))  IS NOT NULL THEN sum(DISTINCT(od))  ELSE 0 END) as total'),
        //         DB::raw('count(DISTINCT(id))  as nop'))
        //         ->first()
        //         ->toArray(),
        //     'motor'=>MotorPolicy::whereBetween('motor_policies.created_at', [$start, $end])->select(
        //         DB::raw('(CASE WHEN sum(DISTINCT(od))  IS NOT NULL THEN sum(DISTINCT(od))  ELSE 0 END)+ (CASE WHEN sum(DISTINCT(addonpremium))  IS NOT NULL THEN sum(DISTINCT(addonpremium))  ELSE 0 END) + (CASE WHEN sum(DISTINCT(tp))  IS NOT NULL THEN sum(DISTINCT(tp))  ELSE 0 END) + (CASE WHEN sum(DISTINCT(pay_to_owner))  IS NOT NULL THEN sum(DISTINCT(pay_to_owner))  ELSE 0 END) as total'),
        //         DB::raw('count(DISTINCT(id))  as nop'))
        //         ->first()
        //         ->toArray(),
        //     'sme'=>SmePolicy::whereBetween('sme_policies.created_at', [$start, $end])->select(
        //         DB::raw('(CASE WHEN sum(DISTINCT(terrorism_premium))  IS NOT NULL THEN sum(DISTINCT(terrorism_premium))  ELSE 0 END) + (CASE WHEN sum(DISTINCT(od))  IS NOT NULL THEN sum(DISTINCT(od))  ELSE 0 END) as total'),
        //         DB::raw('count(DISTINCT(id))  as nop'))
        //         ->first()
        //         ->toArray()
        // ];
        // $data['agent_wise']=[
        //     'health' => DB::table('agents')
        //     ->join('health_policies', 'agents.id', '=', 'health_policies.agent_id')
        //     ->whereBetween('health_policies.created_at', [$start, $end])
        //     ->select('agents.id','agents.code','first_name','middle_name','last_name',
        //         DB::raw('count(DISTINCT(health_policies.id))  as nop'),
        //         DB::raw('(CASE WHEN sum(DISTINCT(health_policies.od))  IS NOT NULL THEN sum(DISTINCT(health_policies.od))  ELSE 0 END) as total')
        //     )->groupBy('agents.id')->limit(10)->get()->sortByDesc('total')->toArray(),
        //     'motor' => DB::table('agents')
        //     ->join('motor_policies', 'agents.id', '=', 'motor_policies.agent_id')
        //     ->whereBetween('motor_policies.created_at', [$start, $end])
        //     ->select('agents.id','agents.code','first_name','middle_name','last_name',
        //         DB::raw('count(DISTINCT(motor_policies.id))  as nop'),
        //         DB::raw('(CASE WHEN sum(DISTINCT(motor_policies.od))  IS NOT NULL THEN sum(DISTINCT(motor_policies.od))  ELSE 0 END)+ (CASE WHEN sum(DISTINCT(motor_policies.addonpremium))  IS NOT NULL THEN sum(DISTINCT(motor_policies.addonpremium))  ELSE 0 END) + (CASE WHEN sum(DISTINCT(motor_policies.tp))  IS NOT NULL THEN sum(DISTINCT(motor_policies.tp))  ELSE 0 END) + (CASE WHEN sum(DISTINCT(motor_policies.pay_to_owner))  IS NOT NULL THEN sum(DISTINCT(motor_policies.pay_to_owner))  ELSE 0 END) as total')
        //     )->groupBy('agents.id')->limit(10)->get()->sortByDesc('total')->toArray(),
        //     'sme' => DB::table('agents')
        //     ->join('sme_policies', 'agents.id', '=', 'sme_policies.agent_id')
        //     ->whereBetween('sme_policies.created_at', [$start, $end])
        //     ->select('agents.id','agents.code','first_name','middle_name','last_name',
        //         DB::raw('count(DISTINCT(sme_policies.id))  as nop'),
        //         DB::raw('(CASE WHEN sum(DISTINCT(sme_policies.terrorism_premium))  IS NOT NULL THEN sum(DISTINCT(sme_policies.terrorism_premium))  ELSE 0 END) + (CASE WHEN sum(DISTINCT(sme_policies.od))  IS NOT NULL THEN sum(DISTINCT(sme_policies.od))  ELSE 0 END) as total')
        //     )->groupBy('agents.id')->limit(10)->get()->sortByDesc('total')->toArray(),
        // ];
        // $data['total_nop']=array_sum(array_column($data['policy_wise'], 'nop'));
        // $data['total']=array_sum(array_column($data['policy_wise'], 'total'));
        return view('carnival.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function data()
    {
        ini_set('max_execution_time', 400000);
        $start=Carbon::parse('2022-12-20')->format('Y-m-d 00:00:00');
        $end = Carbon::parse('2022-12-23')->format('Y-m-d 23:59:59');
        // $companies=Company::with('health_policies','motor_policies','sme_policies')->get();
        // $data['companies']=DB::table('companies')
        //     ->leftjoin('health_policies', 'companies.id', '=', 'health_policies.company_id')
        //     ->leftjoin('motor_policies', 'companies.id', '=', 'motor_policies.company_id')
        //     ->leftjoin('sme_policies', 'companies.id', '=', 'sme_policies.company_id')
        //     ->whereBetween('health_policies.created_at', [$start, $end])
        //     ->orwherebetween('motor_policies.created_at', [$start, $end])
        //     ->orwherebetween('sme_policies.created_at', [$start, $end])
        //     ->select('companies.id', 'name',
        //         DB::raw('count(DISTINCT(health_policies.id)) + count(DISTINCT(motor_policies.id)) + count(DISTINCT(sme_policies.id))  as nop'),
        //         DB::raw('(CASE WHEN sum(DISTINCT(health_policies.od))  IS NOT NULL THEN sum(DISTINCT(health_policies.od))  ELSE 0 END) 
        //         + (CASE WHEN sum(DISTINCT(motor_policies.od))  IS NOT NULL THEN sum(DISTINCT(motor_policies.od))  ELSE 0 END)+ (CASE WHEN sum(DISTINCT(motor_policies.addonpremium))  IS NOT NULL THEN sum(DISTINCT(motor_policies.addonpremium))  ELSE 0 END) + (CASE WHEN sum(DISTINCT(motor_policies.tp))  IS NOT NULL THEN sum(DISTINCT(motor_policies.tp))  ELSE 0 END) + (CASE WHEN sum(DISTINCT(motor_policies.pay_to_owner))  IS NOT NULL THEN sum(DISTINCT(motor_policies.pay_to_owner))  ELSE 0 END)
        //         + (CASE WHEN sum(DISTINCT(sme_policies.terrorism_premium))  IS NOT NULL THEN sum(DISTINCT(sme_policies.terrorism_premium))  ELSE 0 END) + (CASE WHEN sum(DISTINCT(sme_policies.od))  IS NOT NULL THEN sum(DISTINCT(sme_policies.od))  ELSE 0 END) as total')
        //     )
        //     ->groupBy('companies.id')
        //     ->get()
        //     ->sortByDesc('total')
        //     ->toArray();
        $data['policy_wise']=[
            'health'=>HealthPolicy::whereBetween('health_policies.created_at', [$start, $end])->select(
                DB::raw('(CASE WHEN sum(DISTINCT(od))  IS NOT NULL THEN sum(DISTINCT(od))  ELSE 0 END) as total'),
                DB::raw('count(DISTINCT(id))  as nop'))
                ->first()
                ->toArray(),
            'motor'=>MotorPolicy::whereBetween('motor_policies.created_at', [$start, $end])->select(
                DB::raw('(CASE WHEN sum(DISTINCT(od))  IS NOT NULL THEN sum(DISTINCT(od))  ELSE 0 END)+ (CASE WHEN sum(DISTINCT(addonpremium))  IS NOT NULL THEN sum(DISTINCT(addonpremium))  ELSE 0 END) + (CASE WHEN sum(DISTINCT(tp))  IS NOT NULL THEN sum(DISTINCT(tp))  ELSE 0 END) + (CASE WHEN sum(DISTINCT(pay_to_owner))  IS NOT NULL THEN sum(DISTINCT(pay_to_owner))  ELSE 0 END) as total'),
                DB::raw('count(DISTINCT(id))  as nop'))
                ->first()
                ->toArray(),
            'sme'=>SmePolicy::whereBetween('sme_policies.created_at', [$start, $end])->select(
                DB::raw('(CASE WHEN sum(DISTINCT(terrorism_premium))  IS NOT NULL THEN sum(DISTINCT(terrorism_premium))  ELSE 0 END) + (CASE WHEN sum(DISTINCT(od))  IS NOT NULL THEN sum(DISTINCT(od))  ELSE 0 END) as total'),
                DB::raw('count(DISTINCT(id))  as nop'))
                ->first()
                ->toArray()
        ];
        $data['agent_wise']=[
            'health' => DB::table('agents')
            ->join('health_policies', 'agents.id', '=', 'health_policies.agent_id')
            ->whereBetween('health_policies.created_at', [$start, $end])
            ->select('agents.code',
                DB::raw('count(DISTINCT(health_policies.id))  as nop'),
                DB::raw('(CASE WHEN sum(DISTINCT(health_policies.od))  IS NOT NULL THEN sum(DISTINCT(health_policies.od))  ELSE 0 END) as total')
            )->groupBy('agents.id')->get()->sortByDesc('total')->toArray(),
            'motor' => DB::table('agents')
            ->join('motor_policies', 'agents.id', '=', 'motor_policies.agent_id')
            ->whereBetween('motor_policies.created_at', [$start, $end])
            ->select('agents.code',
                DB::raw('count(DISTINCT(motor_policies.id))  as nop'),
                DB::raw('(CASE WHEN sum(DISTINCT(motor_policies.od))  IS NOT NULL THEN sum(DISTINCT(motor_policies.od))  ELSE 0 END)+ (CASE WHEN sum(DISTINCT(motor_policies.addonpremium))  IS NOT NULL THEN sum(DISTINCT(motor_policies.addonpremium))  ELSE 0 END) + (CASE WHEN sum(DISTINCT(motor_policies.tp))  IS NOT NULL THEN sum(DISTINCT(motor_policies.tp))  ELSE 0 END) + (CASE WHEN sum(DISTINCT(motor_policies.pay_to_owner))  IS NOT NULL THEN sum(DISTINCT(motor_policies.pay_to_owner))  ELSE 0 END) as total')
            )->groupBy('agents.id')->get()->sortByDesc('total')->toArray(),
            'sme' => DB::table('agents')
            ->join('sme_policies', 'agents.id', '=', 'sme_policies.agent_id')
            ->whereBetween('sme_policies.created_at', [$start, $end])
            ->select('agents.code',
                DB::raw('count(DISTINCT(sme_policies.id))  as nop'),
                DB::raw('(CASE WHEN sum(DISTINCT(sme_policies.terrorism_premium))  IS NOT NULL THEN sum(DISTINCT(sme_policies.terrorism_premium))  ELSE 0 END) + (CASE WHEN sum(DISTINCT(sme_policies.od))  IS NOT NULL THEN sum(DISTINCT(sme_policies.od))  ELSE 0 END) as total')
            )->groupBy('agents.id')->get()->sortByDesc('total')->toArray(),
        ];
        $key_values = array_column($data['agent_wise']['health'], 'total'); 
        array_multisort($key_values, SORT_DESC, $data['agent_wise']['health']);
        $data['agent_wise']['health']=array_slice($data['agent_wise']['health'],0,10);
        $key_values = array_column($data['agent_wise']['motor'], 'total'); 
        array_multisort($key_values, SORT_DESC, $data['agent_wise']['motor']);
        $data['agent_wise']['motor']=array_slice($data['agent_wise']['motor'],0,10);
        $key_values = array_column($data['agent_wise']['sme'], 'total'); 
        array_multisort($key_values, SORT_DESC, $data['agent_wise']['sme']);
        $data['agent_wise']['sme']=array_slice($data['agent_wise']['sme'],0,10);
        $data['total_nop']=array_sum(array_column($data['policy_wise'], 'nop'));
        $data['total']=array_sum(array_column($data['policy_wise'], 'total'));
        return json_encode($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
