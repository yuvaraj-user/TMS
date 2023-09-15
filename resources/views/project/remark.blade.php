@extends('admin.layouts.app')
@section('content')
<div class="row mt-4">
  <div class="col-md-12">
    <h2 class="mb-4 form-cls">Remark</h2>
    <form class="form-cls" action="{{ url('/project_delete') }}" method="POST">
      @csrf
      <input type="hidden" name="id" value="{{ !empty($project_id) ? $project_id : '' }}">
      <div class="mb-3">
        <label for="remark" class="form-label">Remark</label>
        <textarea class="form-control" name="remark" id="remark"  rows="5" required></textarea>
        <!-- @if($errors->has('project_name'))
        <p class="validation-error text-danger">{{$errors->first('project_name')}}</p>
        @endif -->
      </div>
      <div class="d-flex justify-content-end me-4">
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </form>
  </div>
</div>
@endsection