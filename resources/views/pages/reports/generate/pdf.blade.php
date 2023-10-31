@php
    use Carbon\Carbon;
@endphp
<!DOCTYPE html>
<html>

<head>
    <title>{{ $data['title'] }}</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            font-family: helvetica !important;
            font-size: 10pt;
        }

        .table-all {
            padding-bottom: 15px;
            width: 100%;
            border-spacing: 0;
            vertical-align: middle;
            border-bottom: 1px solid black;
            border-top: 1px solid black;
        }

        .table-all td {
            padding: 3px;
        }
        .page-break{
            page-break-before: always
        }
    </style>
</head>

<body style="margin: 0;padding: 0;position: relative;">

    @for ($i = 0; $i <= 9; $i++)
        @if (!empty($data['policies'][$i]))
            <div class="tableData">
                <p style="text-align: center; font-size: 18px;">Outward Report
                    -{{ $data['title'] }}</p>
                <table class="table-all">
                    <tbody>
                        <tr>
                            <td>
                                <b>Insurance Company :</b>{{ $data['company_id'] }}
                            </td>
                            <td>
                                <b>Insurance Company Branch
                                    :</b>{{ $data['company_branch_id'] }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Branch :</b>{{ $data['irss_branch_id'] }}
                            </td>
                            <td>
                                <b>IMD Name & Code :</b>{{ $data['branch_imd_id'] }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Payment Type
                                    :</b>{{ config('constants.health_policy_payments.payment_type.' . $i + 1) }}
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <table class="comman-data-table ">
                <thead>
                    <tr>
                        <th>Sr No</th>
                        <th>Agent Code</th>
                        <th>Customer Name</th>
                        <th>Product</th>
                        <th>Number</th>
                        <th>Amount</th>
                        <th>Bank</th>
                        <th>Reg.No</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                    @endphp
                    @foreach ($data['policies'][$i] as $key => $policy)
                        @foreach ($policy->payments as $item)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $policy->agent->code }}</td>
                                <td>{{ ucfirst($policy->customer->customer_code) . ' ' . ucfirst($policy->customer->prefix) . ' ' . ucfirst($policy->customer->first_name) . ' ' . ucfirst($policy->customer->last_name) }}
                                </td>
                                <td>{{ $policy->product }}</td>
                                <td>{{ $item->number }}</td>
                                <td>{{ intval($item->amount) }}</td>
                                <td>{{ $item->payment_type == 1 || empty($item->bank) ? ' ' : $item->bank->name }}
                                </td>
                                <td>{{ $policy->product == 'MOTOR' ? $policy->motor_policy_vehicle->registration_no : ' ' }}
                                </td>
                                <td>{{ $policy->remark }}</td>
                            </tr>
                            @php
                                $total += $item->amount;
                            @endphp
                        @endforeach
                    @endforeach
                </tbody>
            </table>

            <div style="height:auto; width:100%;">
                <p style="text-align: center; "> <b>Total: </b> {{ $total }}</p>
            </div>

            {{-- @if (!empty($data['policies'][$i + 1])) --}}
            <div class="page-break"></div>
            {{-- @endif --}}
        @endif
    @endfor

</body>
</html>