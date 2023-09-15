<!DOCTYPE html>
<html>

<head></head>

<body>
    <h5>{{ Ucfirst($employee_report_details->name) }} Weekly Report ({{ date('d-m-Y',strtotime($last_friday_date)) }} to {{ date('d-m-Y',strtotime($yesterday_date)) }})</h5>
    <table class="table">
        <thead style="background-color:#2089bc;color:white;">
            <tr>
                <th>clients</th>
                <th>Projects</th>
                <th>Estimated Hours</th>
                <th>Spent Hours This Week</th>
                <th>Overall Worked Hours</th>
            </tr>
        </thead>
        <tbody>
            @if($employee_report_details->assigned_projects->count() > 0)
            <?php $total_spent_minutes = 0; ?>
            @foreach($employee_report_details->assigned_projects as $ak => $avalue)
            <?php $overall_worked_minutes = 0; ?>
            @foreach($employee_report_details->overall_project_working_hours as $ok => $overvalue)
            @if($overvalue->project_id == $avalue->project_id)
                <?php $overall_worked_minutes += $overvalue->time_taken; ?>
            @endif
            @endforeach
            <?php $actual_minutes = 0; ?>
            @foreach($employee_report_details->task as $tk => $tval)
            @if($tval->project_id == $avalue->project_id)
            <?php 
                $actual_minutes      += $tval->time_taken;
                $total_spent_minutes += $tval->time_taken;
            ?>
            @endif
            @endforeach
            <tr>
                <td style="border: 1px solid;text-align:center;width:150px;">{{ $avalue->client_detail->name }}</td>
                <td style="border: 1px solid;text-align:center;width:150px;">{{ $avalue->project_detail->project_name }}</td>
                <td style="border: 1px solid;text-align:center;width:150px;">{{ minutes_to_hour($avalue->project_detail->estimation_hours * 60) }}</td>
                <td style="border: 1px solid;text-align:center;width:150px;">{{ minutes_to_hour($actual_minutes) }}</td>
                @if($avalue->project_detail->estimation_hours * 60  > $overall_worked_minutes) 
                <td style="border: 1px solid;text-align:center;width:150px;color:green;">{{ minutes_to_hour($overall_worked_minutes) }}</td>
                @elseif($avalue->project_detail->estimation_hours * 60  < $overall_worked_minutes)
                <td style="border: 1px solid;text-align:center;width:150px;color:red;">{{ minutes_to_hour($overall_worked_minutes) }}</td>
                @else 
                <td style="border: 1px solid;text-align:center;width:150px;">{{ minutes_to_hour($overall_worked_minutes) }}</td>
                @endif
            </tr>
            @endforeach
            @endif
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="border: 1px solid;text-align:center;width:150px;">{{ minutes_to_hour($total_spent_minutes) }}</td>
            </tr>
        </tbody>
</body>