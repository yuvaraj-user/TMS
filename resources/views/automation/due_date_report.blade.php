<!DOCTYPE html>
<html>

<head>
</head>

<body>
    @foreach($due_report as $key => $value)
    <h4>Escalation Report</h4>
    <table class="table">
        <thead style="background-color:#2089bc;color:white;">
            <tr>
                <th>Work Order No</th>
                <th>Client Name</th>
                <th>Project Name</th>
                <th>Planned Delivery</th>
                <th>Actual Delivery</th>
                <th>No Of Delay</th>
                <th>Estimated Hours</th>
                <th>Actual Hours</th>
                <th>Saved vs Exceeded</th>
                <th>Order Value</th>
                <th>Receivable</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="border: 1px solid;text-align:center;">{{ $value['work_order_no'] }}</td>
                <td style="border: 1px solid;text-align:center;">{{ $value['client_name']['name']}}</td>
                <td style="border: 1px solid;text-align:center;">{{ $value['project_name'] }}</td>
                <td style="border: 1px solid;text-align:center;">{{ date('d-M-Y',strtotime($value['work_end_date'])) }}</td>
                <td style="border: 1px solid;text-align:center;">{{ isset($value['actual_delivery_date']) ? date('d-M-Y',strtotime($value['actual_delivery_date'])) : '' }}</td>
                @if(isset($value['work_end_date']) && isset($value['actual_delivery_date'])) 
                    <td style="border: 1px solid;text-align:center;">{{ date_delay($value['work_end_date'],$value['actual_delivery_date']) }}</td>
                @else 
                    <td style="border: 1px solid;text-align:center;"></td>
                @endif
                <td style="border: 1px solid;text-align:center;">{{ isset($value['estimation_hours']) ? minutes_to_hour($value['estimation_hours'] * 60) : '' }}</td>
                <td style="border: 1px solid;text-align:center;">{{ isset($value['actual_working_hours']) ? minutes_to_hour($value['actual_working_hours']) : '' }}</td>
                @if(isset($value['estimation_hours']) && isset($value['actual_working_hours']))
                    @if(($value['estimation_hours'] * 60) > $value['actual_working_hours'])
                        <td style="border: 1px solid;text-align:center;color:green;">{{ minutes_to_hour(($value['estimation_hours'] * 60) - $value['actual_working_hours']) }}</td>
                    @elseif(($value['estimation_hours'] * 60) < $value['actual_working_hours'])
                        <td style="border: 1px solid;text-align:center;color:red;">{{ minutes_to_hour(($value['estimation_hours'] * 60) - $value['actual_working_hours']) }}</td>
                    @endif
                @else 
                <td style="border: 1px solid;text-align:center;"></td>
                @endif
                <td style="border: 1px solid;text-align:center;">{{ $value['order_value'] }}</td>
                <td style="border: 1px solid;text-align:center;">{{ $value['collected_till'] }}</td>
            </tr>
        </tbody>
    </table>

    <h4>Developer summary</h4>
    <table class="table">
        <thead style="background-color:#2089bc;color:white;">
            <tr>
                <th>Employees</th>
                <th>Estimated Hours</th>
                <th>Actual Hours</th>
                <th>Saved vs Exceeded</th>
            </tr>
        </thead>
        <tbody>
            @if(count($value['project_assign_details']) > 0)
            @foreach($value['project_assign_details'] as $k => $val)
            <tr>
                <td style="border: 1px solid;word-wrap: break-word;width: 150px;text-align:center;">{{ $val['employee_detail']['name'] }}</td>
                <td style="border: 1px solid;text-align:center;">{{ minutes_to_hour($val['hours'] * 60) }}</td>
                <td style="border: 1px solid;text-align:center;">{{ minutes_to_hour($val['utilized_hours']) }}</td>
                @if($val['remaining_hours'] < 0) <td style="border: 1px solid;color:red;text-align:center;">{{ minutes_to_hour($val['remaining_hours']) }}</td>
                    @else
                    <td style="border: 1px solid;text-align:center;">{{ minutes_to_hour($val['remaining_hours']) }}</td>
                    @endif
            </tr>
            @endforeach
            @endif
        </tbody>
    </table><br><br>
    @endforeach
</body>

</html>