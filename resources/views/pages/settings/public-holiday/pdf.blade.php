<!DOCTYPE html>
<html>
<head>
    <title>Holiday Details</title>
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
            <h2>Holiday Details</h2>
            <table width=100%>
                <thead>
                    <tr>
                       <th>Title</th>
                       <th>Holiday Date</th>
                       <th>Holiday Type</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!$data['holidays']->isEmpty())
                    @foreach ($data['holidays'] as $holiday)
                    <tr>
                    <td>{{$holiday->title}}</td>
                    <td>{{$holiday->date}}</td>
                    <td>@if($holiday->holiday_type == 'F'){{'Fullday'}}@else{{'Halfday'}}@endif</td>
                   </tr>
                     @endforeach
                    @endif

                </tbody>
            </table>
        </div>
    </div>
</body>
</html>