`<!DOCTYPE html>
<html>
<head>
    <title>FDO Details</title>
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
            <h2>FDO Details</h2>
            <table>
                <thead>
                    <tr>
                        @if($data['fdo_code'] != '')<th>Code</th>@endif
                        @if($data['name'] != '')<th>Name</th>@endif
                        @if($data['account_no'] != '')<th>Account Number</th>@endif
                        @if($data['bank_name'] != '')<th>Bank Name</th>@endif
                        @if($data['ifsc_code'] != '')<th>IFSC Code</th>@endif
                        @if($data['pan_no'] != '')<th>Pan Card Number</th>@endif
                    </tr>
                </thead>
                <tbody>
                    @if(!$data['fdos']->isEmpty())
                    @foreach ($data['fdos'] as $fdo)
                    <tr>
                        @if($data['fdo_code'] != '')<td>{{$fdo->code}}</td>@endif
                        @if($data['name'] != '')<td>{{$fdo->prefix .''. $fdo->first_name .' '. $fdo->middle_name .' '. $fdo->last_name}}</td>@endif
                        @if($data['account_no'] != '')<td>{{$fdo->account_number}}</td>@endif
                        @if($data['bank_name'] != '')<td>@if(!empty($fdo->bank)){{$fdo->bank->name}} @endif</td>@endif
                        @if($data['ifsc_code'] != '')<td>{{$fdo->ifsc_code}}</td>@endif
                        @if($data['pan_no'] != '')<td>{{$fdo->pancard_number}}</td>@endif
                    </tr>
                     @endforeach
                    @endif

                </tbody>
            </table>
        </div>
    </div>
</body>
</html>