<!DOCTYPE html>
<html>
<head>
    <title>Agent Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<style type="text/css">
    h2{
        text-align: center;
        font-size:22px;
        margin-bottom:50px;
    }
    table, th {
          border: 1px solid black;
          border-collapse: collapse;
          font-weight: bold;
          word-break: break-all;
          font-size: 12px;
        }
        table td{
            border: 1px solid black;
          border-collapse: collapse;
          word-break: break-all;
          font-size: 12px;
          font-weight: 400;
            padding: 5px;
        }
</style>  
<body>
	<div >
        <div class="col-md-12">
            <h2>Agent Details</h2>
            <table>
                <thead>
                    <tr>
                        @if($data['agent_code'] != '')<th>Code</th>@endif
                        @if($data['name'] != '')<th>Name</th>@endif
                        @if($data['account_no'] != '')<th>Account Number</th>@endif
                        @if($data['bank_name'] != '')<th>Bank Name</th>@endif
                        @if($data['dob'] != '')<th>Date Of Birth</th>@endif
                        @if($data['ifsc_code'] != '')<th>IFSC Code</th>@endif
                        @if($data['pan_no'] != '')<th>Pan Card Number</th>@endif
                        @if($data['created_on'] != '')<th>created on Date</th>@endif
                    </tr>
                </thead>
                <tbody>
                    @if(!$data['agents']->isEmpty())
                    @foreach ($data['agents'] as $agent)
                    <tr>
                        @if($data['agent_code'] != '')<td>{{$agent->code}}</td>@endif
                        @if($data['name'] != '')<td>{{$agent->prefix .''. $agent->first_name .' '. $agent->middle_name .' '. $agent->last_name}}</td>@endif
                        @if($data['account_no'] != '')<td>{{$agent->account_number}}</td>@endif
                        @if($data['bank_name'] != '')<td>{{$agent->bank_name}}</td>@endif
                        @if($data['dob'] != '')<td>{{$agent->dob}}</td>@endif
                        @if($data['ifsc_code'] != '')<td>{{$agent->ifsc_code}}</td>@endif
                        @if($data['pan_no'] != '')<td>{{$agent->pancard_number}}</td>@endif
                        @if($data['created_on'] != '')<td>{{date('d-m-Y', strtotime($agent->created_at))}}</td>@endif
                    </tr>
                     @endforeach
                    @endif

                </tbody>
            </table>
        </div>
    </div>
</body>
</html>