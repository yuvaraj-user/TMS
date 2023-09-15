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
        <input type="text" class="form-control" id="date" name="date" value="{{ !empty($data) ? $data['date'] : date('d-m-Y')}}" readonly>
        @if($errors->has('date'))
        <p class="validation-error text-danger">{{$errors->first('date')}}</p>
        @endif
      </div>
      <div class="mb-3">
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
      </div>
      <div class="mb-3">
        <label for="project_name" class="form-label">Project Name</label>
        <div class="row">
          <div class="col-md-12">
            <select id="project_name" name="project_id" class="select-box form-control">
              <option value="">Select project</option>
              @foreach($projects as $key => $value)
              <option value="{{ $value->project_detail->id }}" @if(!empty($data) && $data['project_id']==$value->project_detail->id) selected @endif>{{ $value->project_detail->project_name }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
      <div class="mb-3">
        <label for="task_name" class="form-label">Task Name</label>
        <input type="text" class="form-control" id="task_name" name="task_name" value="{{ !empty($data) ? $data['task_name'] : ''}}">
        @if($errors->has('task_name'))
        <p class="validation-error text-danger">{{$errors->first('task_name')}}</p>
        @endif
      </div>
      <div class="mb-3">
        <label for="task_description" class="form-label">Task Description</label>
        <textarea class="form-control" name="task_description" id="task_description" rows="5">@if(!empty($data)){{$data['task_description']}}@endif</textarea>
      </div>
      <div class="mb-3">
        <label for="time_taken" class="form-label">Time Taken</label>
        <div class="row">
          <div class="col-md-2">
            <input type="text" class="form-control" id="time_taken" name="time_taken_hour" placeholder="HH" value="{{ !empty($time_taken_hours) ? $time_taken_hours : ''}}">
          </div>
          <div class="col-md-2">
            <input type="text" class="form-control col-md-4" id="time_taken" name="time_taken_minutes" placeholder="MM" value="{{ !empty($time_taken_minutes) ? $time_taken_minutes : ''}}">
          </div>
        </div>
        @if($errors->has('time_taken'))
        <p class="validation-error text-danger">{{$errors->first('time_taken')}}</p>
        @endif
      </div>
      <div class="mb-3">
        <label for="task-status" class="form-label">Status</label>
        <div class="col-md-12">
          <select name="status" id="task-status" class="select-box form-control">
            <option value="work_in_progress" @if(!empty($data) && $data['status']=="work_in_progress" ) selected @elseif(empty($data)) selected @endif>Work In Progress</option>
            <option value="completed" @if(!empty($data) && $data['status']=="completed" ) selected @endif>Completed</option>
            <option value="hold" @if(!empty($data) && $data['status']=="hold" ) selected @endif>Hold</option>
          </select>
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
@endsection