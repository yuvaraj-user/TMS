@extends('admin.layouts.app')
@section('content')
<div class="row mt-4">
  <div class="col-md-12">
    <h2 class="mb-4 form-cls">Remark</h2>
    <form class="form-cls" action="{{ url('/task_delete') }}" method="POST">
      @csrf
      <input type="hidden" name="id" value="{{ !empty($task_id) ? $task_id : '' }}">
      <div class="mb-3">
        <label for="remark" class="form-label">Remark</label>
        <textarea class="form-control" name="remark" id="remark"  rows="5" required></textarea>
      </div>
      <div class="d-flex justify-content-end me-4">
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </form>
  </div>
</div>
@endsection