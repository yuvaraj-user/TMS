<!DOCTYPE html>
<html>

<head></head>

<body>
    @foreach($progress_projects as $key => $value)
    <h4>{{ $key+1 }}. Work Order No: <u>{{ $value->work_order_no }}</u></h4>
    <h4>Project Name: {{ $value->project_name }}</h4>
    <h4>Client Name:  {{ $value->client_name->name }}</h4>
    <table class="table">
        <thead>
            <tr>
                <th style="background-color:#2089bc;color:white;">Project Description</th>
                <td style="border: 1px solid;text-align:center;width:150px;word-wrap: break-word;">{{ $value->project_description }}</td>
            </tr>
            <tr>
                <th style="background-color:#2089bc;color:white;">Order Value</th>
                <td style="border: 1px solid;text-align:center;width:150px;">{{ $value->order_value }}</td>
            </tr>
            <tr>
                <th style="background-color:#2089bc;color:white;">Collected Till</th>
                <td style="border: 1px solid;text-align:center;width:150px;">{{ $value->collected_till }}</td>
            </tr>
            <tr>
                <th style="background-color:#2089bc;color:white;">Work Start Date</th>
                <td style="border: 1px solid;text-align:center;width:250px;">{{ date('d-m-Y',strtotime($value->work_start_date)) }}</td>
            </tr>
            <tr>
                <th style="background-color:#2089bc;color:white;">Work End Date</th>
                <td style="border: 1px solid;text-align:center;width:150px;">{{ date('d-m-Y',strtotime($value->work_end_date)) }}</td>
            </tr>
            <tr>
                <th style="background-color:#2089bc;color:white;">Billable</th>
                <td style="border: 1px solid;text-align:center;width:150px;">{{ $value->is_billable }}</td>
            </tr>
        </thead>
    </table><br>

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
            @foreach($value->project_assign_details as $pk => $pval)
            <?php $employee_actual_minutes = 0; ?>
            @foreach($value->task as $tk => $tval)
            @if($pval->employee_id == $tval->employee_id)
            <?php $employee_actual_minutes += $tval->time_taken;?>
            @endif
            @endforeach
            <tr>
                <td style="border: 1px solid;text-align:center;width:150px;">{{ $pval->employee_detail->name }}</td>
                <td style="border: 1px solid;text-align:center;width:150px;">{{ minutes_to_hour($pval->hours * 60) }}</td>
                <td style="border: 1px solid;text-align:center;width:150px;">{{ minutes_to_hour($employee_actual_minutes) }}</td>
                @if($pval->hours * 60  > $employee_actual_minutes) 
                <td style="border: 1px solid;text-align:center;width:150px;color:green;">{{ minutes_to_hour($pval->hours * 60 - $employee_actual_minutes) }}</td>
                @elseif($pval->hours * 60  < $employee_actual_minutes)
                <td style="border: 1px solid;text-align:center;width:150px;color:red;">{{ minutes_to_hour($pval->hours * 60 - $employee_actual_minutes) }}</td>
                @else 
                <td style="border: 1px solid;text-align:center;width:150px;">{{ minutes_to_hour($pval->hours * 60 - $employee_actual_minutes) }}</td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table><br>
    @endforeach


</body>