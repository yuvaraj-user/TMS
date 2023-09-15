@extends('admin.layouts.app')
@section('content')
@if(Session::has('success'))
<div class="alert alert-success mt-4">
    {{ Session::get('success') }}
</div>
@elseif(Session::has('failed'))
<div class="alert alert-danger mt-4">
    {{ Session::get('failed') }}
</div>
@endif

    <!------  project view modal -------------->
    <div class="modal fade" id="project_view_modal">
        <div class="modal-dialog  modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title modal_project_name"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-5">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <span class="fw-bold">Query No :</span>
                            <span id="q_no"></span>
                        </div>
                        <div class="col-md-4">
                            <span class="fw-bold">Work Order No : </span>
                            <span id="w_o_no"></span>
                        </div>
                        <div class="col-md-4">
                            <span class="fw-bold">Project Name : </span>
                            <span id="p_name"></span>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <span class="fw-bold">Project Description : </span>
                            <span id="p_description" class="text-break"></span>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <span class="fw-bold">Order Value : </span>
                            <span id="o_value"></span>
                        </div>
                        <div class="col-md-4">
                            <span class="fw-bold">Collected Till : </span>
                            <span id="c_till"></span>
                        </div>
                        <div class="col-md-4">
                            <span class="fw-bold">Billable : </span>
                            <span id="billable"></span>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <span class="fw-bold">Work Start Date : </span>
                            <span id="w_s_date"></span>
                        </div>
                        <div class="col-md-4">
                            <span class="fw-bold">Work End Date : </span>
                            <span id="w_e_date"></span>
                        </div>
                        <div class="col-md-4">
                            <span class="fw-bold">Actual Delivery Date : </span>
                            <span id="a_d_date"></span>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <span class="fw-bold">Work Status : </span>
                            <span id="w_status"></span>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <h5>Project Assigned Details</h5>
                        <table class="table table-bordered table-striped mt-2">
                            <thead class="bg-info text-white">
                                <tr>
                                    <th>No</th>
                                    <th>Employee</th>
                                    <th>Estimation Hours</th>
                                    <th>Actual Spent Hours</th>
                                    <th>Saved or Exceeded</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="assigned_details">

                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!------  project view modal end -------------->

<div class="container mt-4">
    <h2 class="mb-4">Consolidated Report</h2>
    <form method="GET" name="filter">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="client_id" class="form-label">Client Name</label>
                <div class="row">
                    <div class="col-md-12">
                        <select id="client_id" name="client_id" class="select-box form-control">
                            <option value="">Select client</option>
                            @foreach($clients as $key => $value)
                            <option value="{{ $value->id }}" @if(!empty($client_id) && $client_id==$value->id) selected @endif>{{ $value->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <label for="project_id" class="form-label">Project Name</label>
                <div class="row">
                    <div class="col-md-12">
                        <select id="project_id" name="project_id" class="select-box form-control">
                            <option value="">Select project</option>
                            @foreach($all_projects as $key => $value)
                            <option value="{{ $value->id }}" @if(!empty($project_id) && $project_id==$value->id) selected @endif>{{ $value->project_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <label for="work-status" class="form-label">Status</label>
                <div class="col-md-12">
                    <select name="status" id="work-status" class="select-box form-control">
                        <option value="" @if(empty($work_status)) selected @endif>Select Status</option>
                        <option value="all" @if(!empty($work_status) && $work_status=="all" ) selected @endif>All</option>
                        <option value="yet_to_start" @if(!empty($work_status) && $work_status=="yet_to_start" ) selected @endif>Yet To Start</option>
                        <option value="work_in_progress" @if(!empty($work_status) && $work_status=="work_in_progress" ) selected @endif>Work In Progress</option>
                        <option value="completed" @if(!empty($work_status) && $work_status=="completed" ) selected @endif>Completed</option>
                        <option value="hold" @if(!empty($work_status) && $work_status=="hold" ) selected @endif>Hold</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4 mb-3">
                <label for="employee_id" class="form-label">Employee Name</label>
                <div class="row">
                    <div class="col-md-12">
                        <select id="employee_id" name="employee_id" class="select-box form-control">
                            <option value="">Select employee</option>
                            @foreach($employees as $key => $value)
                            <option value="{{ $value->id }}" @if(!empty($employee_id) && $employee_id==$value->id) selected @endif>{{ $value->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <label>Date</label>
                <div class="input-daterange input-group" id="datepicker6" data-date-format="dd-mm-yyyy" data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker6'>
                    <input type="text" class="form-control" name="start_date" placeholder="Start Date" autocomplete="off" value="{{ !empty($start_date) ? date('d-m-Y',strtotime($start_date)) : ''}}" />
                    <input type="text" class="form-control" name="end_date" placeholder="End Date" autocomplete="off" value="{{ !empty($end_date) ? date('d-m-Y',strtotime($end_date)) : ''}}" />
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <button type="submit" class="col-md-3 btn btn-info mt-4"><i class="fa fa-search"></i> Filter</button>
                <a href="{{ route('consolidated_report') }}" class="col-md-3"><button type="button" class="btn btn-danger mt-4"><i class="fa-solid fa-rotate-right"></i> Clear Search</button></a>
            </div>
        </div>
    </form>

    <table class="table table-bordered project-datatable" id="datatable">
        <thead>
            <tr>
                <th>SNo</th>
                <th>Client</th>
                <th>Project</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Planned Man Hours</th>
                <th>Hours Completed</th>
                <th>Hours Available</th>
                <th>Work Status</th>
                <th>Order Value</th>
                <th>Collected Till</th>
            </tr>
        </thead>
        <tbody>
            @foreach($projects as $key => $val)
            <?php $available_hours = ($val->planned_man_hours * 60) - $val->completed_man_minutes; ?>
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ !empty($val->client_name) ? $val->client_name->name : '' }}</td>
                <td><a href="javascript:void(0)" class="view-btn" data-id={{$val->id}}>{{ $val->project_name }}</a></td>
                <td>{{ date('d-m-Y',strtotime($val->work_start_date)) }}</td>
                <td>{{ date('d-m-Y',strtotime($val->work_end_date)) }}</td>
                <td>{{ $val->planned_man_hours }}</td>
                <td>{{ minutes_to_hour($val->completed_man_minutes) }}</td>
                <td @if($available_hours < 0) class="text-danger" @endif>{{ minutes_to_hour($available_hours) }}</td>
                <td>{{ $val->work_status }}</td>
                <td>{{ $val->order_value }}</td>
                <td>{{ $val->collected_till }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script type="text/javascript">
    var base_path = '<?php echo url('/'); ?>';
    $(document).ready(function() {
        $('#datatable').DataTable({
            dom: 'frtipB',
            scrollX: true,
            columnDefs: [{
                "width": "70px",
                "targets": [3, 4]
            }],
            buttons: [{
                    extend: 'copy',
                    title: 'consolidated_report'
                },
                {
                    extend: 'csv',
                    title: 'consolidated_report'
                },
                {
                    extend: 'excel',
                    title: '',
                    filename: 'consolidated_report'
                },
                {
                    extend: 'pdf',
                    title: 'consolidated_report'
                },
                {
                    extend: 'print',
                    title: 'consolidated_report'
                }
            ]
        });
    });
    $(document).on('click', '.view-btn', function() {
        var id = $(this).data('id');
        $.ajax({
            url: base_path + "/project_view/" + id,
            type: "GET",
            success: function(res) {
                var work_s_date = moment(new Date(res.project_det.work_start_date)).format('DD-MM-YYYY');
                var work_e_date = moment(new Date(res.project_det.work_end_date)).format('DD-MM-YYYY');
                $('.modal_project_name').text(res.project_det.project_name.toUpperCase());
                $('#q_no').text(res.project_det.query_no);
                $('#w_o_no').text(res.project_det.work_order_no);
                $('#p_name').text(res.project_det.project_name);
                $('#p_description').text(res.project_det.project_description);
                $('#o_value').text(res.project_det.order_value);
                $('#c_till').text(res.project_det.collected_till);
                $('#billable').text(res.project_det.is_billable);
                $('#w_s_date').text(work_s_date);
                $('#w_e_date').text(work_e_date);
                $('#w_status').text(res.project_det.work_status);
                $('#a_d_date').text(res.project_det.actual_delivery_date_format);

                var html = '';
                var i = 1;
                var total_spent_minutes = 0;
                var total_save_or_exceed = 0; 
                for (let value of res.project_det.project_assign_details) {
                    var actual_spent_minutes = 0;
                    for (let task_val of res.project_det.task) {
                        if (task_val.employee_id == value.employee_id) {
                            actual_spent_minutes += parseInt(task_val.time_taken);
                            total_spent_minutes  += parseInt(task_val.time_taken);
                        }
                    }
                    total_save_or_exceed += parseInt(value.hours) * 60 - parseInt(actual_spent_minutes);
                    html += `<tr>`;
                    html += `<td>` + i + `</td>`;
                    html += `<td>` + value.employee_detail.name + `</td>`;
                    html += `<td>` + minutes_to_hour(parseInt(value.hours) * 60) + `</td>`;
                    html += `<td>` + minutes_to_hour(actual_spent_minutes) + `</td>`;
                    html += `<td>` + minutes_to_hour(parseInt(value.hours) * 60 - parseInt(actual_spent_minutes)) + `</td>`;
                    html += `<td><a href="`+base_path+`/daily_report?project_id=`+res.project_det.id+`&employee_id=`+value.employee_id+`" style="width:60px;height: 30px;" target="_blank" class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> view</a></td>`;
                    html += `</tr>`;
                    i++;
                }
                if(Object.keys(res.project_det.project_assign_details).length > 0) {
                    html += `<tr>`;
                    html += `<td></td>`;
                    html += `<td></td>`;
                    html += `<td class="fw-bold">`+ minutes_to_hour(res.project_det.estimation_hours * 60) +`</td>`;
                    html += `<td class="fw-bold">`+ minutes_to_hour(total_spent_minutes) +`</td>`;
                    html += `<td class="fw-bold">`+ minutes_to_hour(total_save_or_exceed) +`</td>`;
                    html += `</tr>`;
                }
                $('#assigned_details').html(html);
                $('#project_view_modal').modal('show');
            },
        });
    });

    function minutes_to_hour(minutes) {
        var total_minutes = minutes;
        var time_taken_hours = parseInt(total_minutes / 60);
        var time_taken_minutes = total_minutes - (time_taken_hours * 60);
        return time_format_change(time_taken_hours)+
        ": "+time_format_change(time_taken_minutes)+
        "";
    }

    function time_format_change(value) {
        if (value.length > 0 && value.length == 1) {
            value = '0'+value;
        }
        else if(value == 0) {
            value = '0'+value;
        }
        return value;
    }
</script>
@endsection