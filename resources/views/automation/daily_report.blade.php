<!DOCTYPE html>
<html>

<head>
</head>

<body>
    @foreach($daily_reports as $eid => $value)
    <h4>{{ Ucfirst(employee_name($eid)) }}</h4>
    <table class="table" @if(empty($value[0])) style="width:85%;" @endif>
        <thead style="background-color:blue;color:white;">
            <tr>
                <th>SNo</th>
                <th>Client Name</th>
                <th>Project Name</th>
                <th>Estimated Hours</th>
                <th>Delivery Date</th>
                <th>Task</th>
                <th>Task Description</th>
                <th>Today Spent Hours</th>
                <th>Completed Hours</th>
            </tr>
        </thead>
        @if(!empty($value[0]))
        <tbody>
            <?php $total_minutes_taken_today = 0; ?>
            @foreach($value as $k => $val)
            <?php
            $total_minutes_taken_today   += $val['time_taken'];
            $total_completed_hours       = completed_estimation_minutes($val['project_detail']['id'], $val['employee_id']);
            ?>
            <tr>
                <td style="border: 1px solid;text-align:center;">{{ $k + 1 }}</td>
                <td style="border: 1px solid;text-align:center;width:150px;text-align:center;">{{ !empty($val['client_detail']) ? $val['client_detail']['name'] : '' }}</td>
                <td style="border: 1px solid;word-wrap:break-word;width:150px;text-align:center;">{{ !empty($val['project_detail']) ? $val['project_detail']['project_name'] : ''}}</td>
                <td style="border: 1px solid;text-align:center;">{{ man_estimation_hours($val['project_detail']['id'],$val['employee_id']) }}</td>
                <td style="border: 1px solid;text-align:center;">{{ !empty($val['project_detail']) ? $val['project_detail']['work_end_date'] : '' }}</td>
                <td style="border: 1px solid;word-wrap:break-word;width:150px;text-align:center;">{{ $val['task_name'] }}</td>
                <td style="border: 1px solid;word-wrap:break-word;width:150px;text-align:center;">{{ $val['task_description'] }}</td>
                <td style="border: 1px solid;text-align:center;">{{ minutes_to_hour($val['time_taken']) }}</td>
                <td style="border: 1px solid;text-align:center;">{{ minutes_to_hour($total_completed_hours) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tbody>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="font-weight: bold;text-align:end;">Total Hours =</td>
                <td style="border: 1px solid;text-align:center;">{{ minutes_to_hour($total_minutes_taken_today) }}</td>
                <td></td>
            </tr>
        </tbody>
        @else
        <tbody>
            <tr>
                <td style="border: 1px solid;text-align:center;text-align:center;" colspan="9">
                    <p>NIL</p>
                </td>
            </tr>
        </tbody>
        @endif
    </table>
    @endforeach
</body>

</html>