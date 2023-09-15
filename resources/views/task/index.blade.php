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
<div class="container mt-4">
    <div class="d-flex justify-content-end">
        <a href="{{ route('task_add')}}"><button type="button" class="btn btn-primary" name="add_project"><i class="fa fa-plus" aria-hidden="true"></i> Add Man Hours</button></a>
    </div>
    <h2 class="mb-4 form-cls">Man Hours</h2>
    <table class="table table-bordered project-datatable" id="datatable">
        <thead>
            <tr>
                <th>No</th>
                <th>Date</th>
                <th>Client Name</th>
                <th>Project Name</th>
                <th>Task Name</th>
                <th>Time Taken</th>
                <th>Status</th>
                <th>Action</th>
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
            dom: "frtipB",
            ajax: "{{ route('task') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'task_date',
                    name: 'date'
                },
                {
                    data: 'client_name',
                    name: 'client_name'
                },
                {
                    data: 'project_name',
                    name: 'project_name'
                },
                {
                    data: 'task_name',
                    name: 'task_name'
                },
                {
                    data: 'man_hours',
                    name: 'man_hours'
                },
                {
                    data: 'status',
                    name: 'status'
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
    $(document).on('click','.edit-btn',function(){
        var id = $(this).data('id');
        window.location.href = base_path+"/task_edit/"+id;
    });
    $(document).on('click','.delete-btn',function(){
        var id = $(this).data('id');
        window.location.href = base_path+"/task_remark/"+id;
    });
</script>
@endsection