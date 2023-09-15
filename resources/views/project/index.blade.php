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
<div class="modal fade" id="project_view">
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
    <div class="d-flex justify-content-end">
        <a href="{{ route('project_add')}}" class=""><button type="button" class="btn btn-primary" name="add_project"><i class="fa fa-plus" aria-hidden="true"></i> Add Project</button></a>
    </div>
    <h2 class="mb-4 form-cls">Projects</h2>
    <table class="table table-bordered project-datatable" id="datatable">
        <thead>
            <tr>
                <th>No</th>
                <th>Query No</th>
                <th>Work Order No</th>
                <th>Client Name</th>
                <th>Project Name</th>
                <th>Order Value</th>
                <th>Work Start Date</th>
                <th>Work End Date</th>
                <th>Work Status</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<script type="text/javascript">
    var base_path = '<?php echo url('/'); ?>';
    $(function() {
        var table = $('.project-datatable').DataTable({
            processing: true,
            serverSide: true,
            scrollX : true,
            dom: "frtipB",
            ajax: "{{ route('project') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'query_no',
                    name: 'query_no'
                },
                {
                    data: 'work_order_no',
                    name: 'work_order_no'
                },
                {
                    data: 'client_name',
                    name: 'client_name',
                    width: '220px'
                },
                {
                    data: 'project_name',
                    name: 'project_name',
                    width: '220px'
                },
                {
                    data: 'order_value',
                    name: 'order_value'
                },
                {
                    data: 'work_s_d',
                    name: 'work_start_date',
                    width: '60px'
                },
                {
                    data: 'work_e_d',
                    name: 'work_end_date',
                    width: '60px'
                },
                {
                    data: 'work_status',
                    name: 'work_status',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: true,
                    searchable: false
                },
            ]
        });

    });
    $(document).on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        window.location.href = base_path + "/project_edit/" + id;
    });
    $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        window.location.href = base_path + "/project_remark/" + id;
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
                $('#project_view').modal('show');
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