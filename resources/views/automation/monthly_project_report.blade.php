<!DOCTYPE html>
<html>

<head></head>

<body>

    <!------------ billable and non_billable project wise table -------->
    <h3>Hourly report for the period of {{ date('d-m-Y',strtotime($start_date)) }} to {{ date('d-m-Y',strtotime($end_date)) }}</h3>
    <table class="table">
        <thead style="background-color:#2089bc;color:white;">
            <tr>
                <th>Projects</th>
                @foreach($employees as $key => $value)
                <th>{{ Ucfirst($value->name) }}</th>
                @endforeach
                <th>Total Project Working Hours</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="border: 1px solid;text-align:center;width:150px;">Billable Projects</td>
                <?php $total_billable_project_hours = 0; ?>
                @foreach($employees as $emk => $emvalue)
                <?php $billable_employee_hours = 0; ?>
                @foreach($billable as $bk => $bvalue)
                @foreach($bvalue->task as $btk => $btvalue)
                <?php
                if ($btvalue->employee_id == $emvalue->id) {
                    $billable_employee_hours += $btvalue->time_taken;
                }
                ?>
                @endforeach
                @endforeach
                <?php $total_billable_project_hours += $billable_employee_hours; ?>
                <td style="border: 1px solid;text-align:center;width:120px;">@if( trim(minutes_to_hour($billable_employee_hours)) != '00 : 00' ) {{ minutes_to_hour($billable_employee_hours) }} @else - @endif</td>
                @endforeach
                <td style="border: 1px solid;text-align:center;width:120px;">@if( trim(minutes_to_hour($total_billable_project_hours)) != '00 : 00' ) {{ minutes_to_hour($total_billable_project_hours) }} @else - @endif</td>
            </tr>

            <tr>
                <td style="border: 1px solid;text-align:center;width:150px;">Non Billable Projects</td>
                <?php $total_non_billable_project_hours = 0; ?>
                @foreach($employees as $emk => $emvalue)
                <?php $non_billable_employee_hours = 0; ?>
                @foreach($non_billable as $nbk => $nbvalue)
                @foreach($nbvalue->task as $nbtk => $nbtvalue)
                <?php
                if ($nbtvalue->employee_id == $emvalue->id) {
                    $non_billable_employee_hours += $nbtvalue->time_taken;
                }
                ?>
                @endforeach
                @endforeach
                <?php $total_non_billable_project_hours += $non_billable_employee_hours; ?>
                <td style="border: 1px solid;text-align:center;width:120px;">@if( trim(minutes_to_hour($non_billable_employee_hours)) != '00 : 00' ) {{ minutes_to_hour($non_billable_employee_hours) }} @else - @endif</td>
                @endforeach
                <td style="border: 1px solid;text-align:center;width:120px;">@if( trim(minutes_to_hour($total_non_billable_project_hours)) != '00 : 00' ) {{ minutes_to_hour($total_non_billable_project_hours) }} @else - @endif</td>
            </tr>

            <!------ total hours row ---->
            <tr>
                <td></td>
                @foreach($employees as $k => $val)
                <td style="border: 1px solid;text-align:center;width:120px;background-color:#2089bc;color:white;">@if( trim(minutes_to_hour($val->task_sum_time_taken)) != '00 : 00' ) {{ minutes_to_hour($val->task_sum_time_taken) }} @else - @endif</td>
                @endforeach
                <td style="border: 1px solid;text-align:center;width:120px;background-color:#7ebc20;color:white;">@if( trim(minutes_to_hour($project_total_hours)) != '00 : 00' ) {{ minutes_to_hour($project_total_hours) }} @else - @endif</td>
            </tr>
            <!------ total hours row end ---->

        </tbody>
    </table>
    <!------------ billable and non_billable project wise table end-------->


    <h4>Client Summary:-</h4>
    <table class="table">
        <thead style="background-color:#2089bc;color:white;">
            <tr>
                <th>Client</th>
                <th>Project</th>
                @foreach($employees as $key => $value)
                <th>{{ Ucfirst($value->name) }}</th>
                @endforeach
                <th>Total Project Working Hours</th>
            </tr>
        </thead>
        <tbody>
            @foreach($projects as $key => $value)
            <?php $total_project_working_hours = 0; ?>
            <tr>
                <td style="border: 1px solid;text-align:center;width:150px;">{{ $value->client_name['name'] }}</td>
                <td style="border: 1px solid;text-align:center;width:150px;">{{ $value->project_name }}</td>
                @foreach($employees as $k => $val)
                <?php $employee_total_time = 0; ?>
                @foreach($value->task as $tk => $tval)
                @if($val->id == $tval->employee_id)
                <?php $employee_total_time += $tval->time_taken; ?>
                @endif
                @endforeach
                <?php $total_project_working_hours += $employee_total_time; ?>
                <td style="border: 1px solid;text-align:center;width:120px;">@if( trim(minutes_to_hour($employee_total_time)) != '00 : 00' ) {{ minutes_to_hour($employee_total_time) }} @else - @endif</td>
                @endforeach
                <td style="border: 1px solid;text-align:center;width:120px;">@if( trim(minutes_to_hour($total_project_working_hours)) != '00 : 00' ) {{ minutes_to_hour($total_project_working_hours) }} @else - @endif</td>
            </tr>
            @endforeach

            <!------ total hours row ---->
            <tr>
                <td></td>
                @foreach($employees as $k => $val)
                <td style="border: 1px solid;text-align:center;width:120px;background-color:#2089bc;color:white;">@if( trim(minutes_to_hour($val->task_sum_time_taken)) != '00 : 00' ) {{ minutes_to_hour($val->task_sum_time_taken) }}  @else - @endif</td>
                @endforeach
                <td style="border: 1px solid;text-align:center;width:120px;background-color:#7ebc20;color:white;">@if( trim(minutes_to_hour($project_total_hours)) != '00 : 00' ) {{ minutes_to_hour($project_total_hours) }}  @else - @endif</td>
            </tr>
            <!------ total hours row end ---->

        </tbody>
    </table>
</body>

</html>