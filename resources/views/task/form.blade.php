@extends('admin.layouts.app')
@section('content')
<div class="row mt-4">
  <div class="col-md-2">
  </div>
  <div class="col-md-8">
    <h2 class="mb-4 form-cls text-info">@if(Request::segment(1) == "task_add") Add Man Hours @else Edit Man Hours @endif</h2>
    <form class="form-cls " action="{{ url('/task_store') }}" method="POST">
      @csrf
      @if(!empty($data))
      <input type="hidden" name="_method" value="PATCH">
      <input type="hidden" name="id" value="{{ $data->id }}">
      @endif
      <div class="mb-3">
        <label for="date" class="form-label">Date</label>
        @if(\Auth::user()->role == 1) 
        <input type="text" class="form-control datepicker" id="date" name="date" value="{{ !empty($data) ? $data['date'] : date('d-m-Y')}}" autocomplete="off">
        @else 
        <input type="text" class="form-control" id="date" name="date" value="{{ !empty($data) ? $data['date'] : date('d-m-Y')}}" readonly autocomplete="off">
        @endif
        @if($errors->has('date'))
        <p class="validation-error text-danger">{{$errors->first('date')}}</p>
        @endif
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="client_name" class="form-label">Client Name</label>
          <div class="row">
            <div class="col-md-12">
              <select id="client_name" name="client_id" class="select-box form-control">
                <option value="">Select client</option>
                @foreach($clients as $key => $value)
                <option value="{{ $value->id }}" @if(!empty($data) && $data['client_id']==$value->id) selected @endif>{{ $value->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          @if($errors->has('client_id'))
          <p class="validation-error text-danger">{{$errors->first('client_id')}}</p>
          @endif
        </div>

        <div class="col-md-6 mb-3">
          <label for="project_name" class="form-label">Project Name</label>
          <div class="row project-row">
            <div class="col-md-12">
              <select id="project_name" name="project_id" class="select-box form-control">
                <option value="">Select project</option>
                @if(!empty($data) && $data['project_id'])
                @foreach($projects as $key => $value)
                <option value="{{ $value->id }}" @if($data['project_id']==$value->id) selected @endif>{{ $value->project_name }}</option>
                @endforeach
                @endif
              </select>
            </div>
          </div>
          @if($errors->has('project_id'))
          <p class="validation-error text-danger">{{$errors->first('project_id')}}</p>
          @endif
        </div>
      </div>

      <div class="mb-3">
        <label for="task_name" class="form-label">Task Name</label>
        <input type="text" class="form-control" id="task_name" name="task_name" value="{{ !empty($data) ? $data['task_name'] : ''}}" autocomplete="off">
        @if($errors->has('task_name'))
        <p class="validation-error text-danger">{{$errors->first('task_name')}}</p>
        @endif
      </div>

      <div class="mb-3">
        <label for="task_description" class="form-label">Task Description</label>
        <textarea class="form-control" name="task_description" id="task_description" rows="5" autocomplete="off">@if(!empty($data)){{$data['task_description']}}@endif</textarea>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="time_taken" class="form-label">Time Taken</label>
          <div class="row">
            <div class="col-md-4">
              <input type="number" class="form-control" min="0" max="24" onpaste="return false;" id="time_taken_hour" name="time_taken_hour" placeholder="HH" value="{{ !empty($time_taken_hours) ? $time_taken_hours : '00'}}" autocomplete="off">
            </div>
            <div class="col-md-4">
              <input type="number" class="form-control col-md-4" min="0" max="60" maxlength="2" onpaste="return false;" id="time_taken_minutes" name="time_taken_minutes" placeholder="MM" value="{{ !empty($time_taken_minutes) ? $time_taken_minutes : '00'}}" autocomplete="off">
            </div>
          </div>
          @if($errors->has('time_taken_hour'))
            <p class="validation-error text-danger">{{$errors->first('time_taken_hour')}}</p>
          @elseif($errors->has('time_taken_minutes'))
            <p class="validation-error text-danger">{{$errors->first('time_taken_minutes')}}</p>
          @endif
        </div>

        <div class="col-md-6 mb-3">
          <label for="task-status" class="form-label">Status</label>
          <div class="col-md-12">
            <select name="status" id="task-status" class="select-box form-control">
              <option value="work_in_progress" @if(empty($data)) selected @endif>Select Status</option>
              <option value="work_in_progress" @if(!empty($data) && $data['status']=="work_in_progress" ) selected @endif>Work In Progress</option>
              <option value="completed" @if(!empty($data) && $data['status']=="completed" ) selected @endif>Completed</option>
              <option value="hold" @if(!empty($data) && $data['status']=="hold" ) selected @endif>Hold</option>
            </select>
          </div>
        </div>
      </div>
      <div class="mb-3">
        <label for="status_remarks" class="form-label">Status Remarks</label>
        <textarea class="form-control" name="status_remarks" id="status_remarks" rows="5">@if(!empty($data)){{$data['status_remarks']}}@endif</textarea>
      </div>
      <div class="d-flex justify-content-end me-4">
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </form>
  </div>
  <div class="col-md-2">
  </div>
</div>
<script>
  var base_path = "<?php echo url('/'); ?>";
  // $(document).ready(function() {
  //   var client = $('#client_name').find(':selected').val();
  //   if (client) {
  //     assigned_project_get(client);
  //   }
  // });
  $('#time_taken_minutes').on('focusout', function(e) {
    var value = $(this).val();
    if (value.length < 2 && value.length != 0) {
      $(this).val('0' + value);
    } else if (value.length == 0) {
      $(this).val('00');
    } else if (value[0] == '-') {
      $(this).val('00');
    } else if (value > 60) {
      $(this).val('00');
    }
  });

  $('#time_taken_hour').on('focusout', function(e) {
    var value = $(this).val();
    if (value.length < 2 && value.length != 0) {
      $(this).val('0' + value);
    } else if(value.length == 0) {
      $(this).val('00');
    } else if(value[0] == '-') {
      $(this).val('00');
    } else if(value > 24) {
      $(this).val('00');
    }
  });

  $('#client_name').on('change', function() {
    var client_id = $(this).val();
    assigned_project_get(client_id);
  });

  function assigned_project_get(client_id) {
    var html = "";
    $.ajax({
      url: base_path + "/assigned_project_get",
      type: "GET",
      data: {
        client_id: client_id
      },
      success: function(res) {
        if (res.role == 2) {
          for (i in res.projects) {
            if (i == 0) {
              html += '<option value="">Select project</option>';
            }
            html += '<option value="' + res.projects[i].id + '">' + res.projects[i].project_name + '</option>';
          }
          $('#project_name').html(html);
        } else if (res.role == 1) {
          for (i in res.projects) {
            if (i == 0) {
              html += '<option value="">Select project</option>'
            }
            html += '<option value="' + res.projects[i].id + '">' + res.projects[i].project_name + '</option>';
          }
          $('#project_name').html(html);
        }
      
      }
    });
  }
</script>
@endsection