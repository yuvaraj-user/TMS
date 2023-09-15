<!DOCTYPE html>
<html>

<head></head>

<body>
    <h4>Software Development - Project Delivery report for the month of {{ date('F Y',strtotime($start_date)) }} </h4>
    <table class="table">
        <thead style="background-color:#2089bc;color:white;">
            <tr>
                <th>S No</th>
                <th>Work Order No</th>
                <th>Project Name</th>
                <th>Client Name</th>
                <th>Planned Delivery</th>
                <th>Actual Delivery</th>
                <th>No of Delay</th>
                <th>Estimated Hours</th>
                <th>Actual Hours</th>
                <th>Saved vs Exceeded</th>
                <th>Order Value</th>
                <th>Receivable</th>
            </tr>
        </thead>
        <tbody>
            @foreach($delivered_projects as $key => $value)
            <?php 
            $no_of_day_delay = date_delay($value->work_end_date,$value->actual_delivery_date);
             ?>
            <tr>
                <td style="border: 1px solid;text-align:center;width:150px;">{{ $key + 1 }}</td>
                <td style="border: 1px solid;text-align:center;width:150px;">{{ $value->work_order_no }}</td>
                <td style="border: 1px solid;text-align:center;width:150px;">{{ $value->project_name }}</td>
                <td style="border: 1px solid;text-align:center;width:150px;">{{ $value->client_name->name }}</td>
                <td style="border: 1px solid;text-align:center;width:150px;">{{ date('d-m-Y', strtotime($value->work_end_date)) }}</td>
                <td style="border: 1px solid;text-align:center;width:150px;">{{ date('d-m-Y', strtotime($value->actual_delivery_date)) }}</td>
                @if(($no_of_day_delay < 0))
                <td style="border: 1px solid;text-align:center;width:150px;color:red;">{{ ($no_of_day_delay < 0) ? $no_of_day_delay : "No delay" }}</td>
                @else 
                <td style="border: 1px solid;text-align:center;width:150px;color:green;">{{ ($no_of_day_delay < 0) ? $no_of_day_delay : "No delay" }}</td>
                @endif
                <td style="border: 1px solid;text-align:center;width:150px;">{{ minutes_to_hour($value->estimation_hours * 60) }}</td>
                <td style="border: 1px solid;text-align:center;width:150px;">{{ minutes_to_hour($value->actual_working_hours) }}</td>
                @if(($value->estimation_hours * 60) - $value->actual_working_hours < 0)
                    <td style="border: 1px solid;text-align:center;width:150px;color:red;">{{ minutes_to_hour(($value->estimation_hours * 60) - $value->actual_working_hours) }}</td>
                @else 
                    <td style="border: 1px solid;text-align:center;width:150px;color:green;">{{ minutes_to_hour(($value->estimation_hours * 60) - $value->actual_working_hours) }}</td>
                @endif
                <td style="border: 1px solid;text-align:center;width:150px;">{{ $value->order_value }}</td>
                <td style="border: 1px solid;text-align:center;width:150px;">{{ $value->collected_till }}</td>

            </tr>
            @endforeach
        </tbody>
    </table>
</body>