@extends('admin.layouts.app')
@section('content')

<!------  client add modal -------------->
<div class="modal fade" id="client_add">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New Client</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <label for="client" class="form-label">Client Name</label>
        <input type="text" class="form-control" id="client" name="client">
        <p class="validation-error text-danger client_creation_err"></p>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
        <button type="button" class="btn btn-primary" id="client_btn"><i class="fa fa-plus" aria-hidden="true"></i> Add</button>
      </div>
    </div>
  </div>
</div>
<!------  client add modal end -------------->

<div class="row mt-4">
  <div class="col-md-2">
  </div>
  <div class="col-md-8">
    <h2 class="mb-4 form-cls text-info">@if(Request::segment(1) == "project_add") Add Project @else Edit Project @endif</h2>
    <form class="form-cls" action="{{ url('/project_store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      @if(!empty($data))
      <input type="hidden" name="_method" value="PATCH">
      <input type="hidden" name="id" value="{{ $data->id }}">
      @endif

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="query_no" class="form-label">Query No</label>
          <input type="text" class="form-control" id="query_no" name="query_no" value="{{ !empty($data) ? $data['query_no'] : ''}}" autocomplete="off" onkeypress="return isNumber(event)">
          @if($errors->has('query_no'))
          <p class="validation-error text-danger">{{$errors->first('query_no')}}</p>
          @endif
        </div>

        <div class="col-md-6 mb-3">
          <label for="work_order" class="form-label">Work Order No</label>
          <input type="text" class="form-control" id="work_order" name="work_order" value="{{ !empty($data) ? $data['work_order_no'] : ''}}" autocomplete="off" onkeypress="return isNumber(event)" required>
          @if($errors->has('work_order'))
          <p class="validation-error text-danger">{{$errors->first('work_order')}}</p>
          @endif
        </div>
      </div>

      <div class="mb-3">
        <label for="client_name" class="form-label">Client Name</label>
        <div class="row">
          <div class="col-md-9">
            <select id="client_name" name="client_id" class="select-box form-control">
              <option value="">Select client</option>
              @foreach($clients as $key => $value)
              <option value="{{ $value->id }}" @if(!empty($data) && $data['client_id']==$value->id) selected @endif>{{ $value->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#client_add"><i class="fa fa-user-secret" aria-hidden="true"></i> New Client</button>
          </div>
        </div>
        @if($errors->has('client_id'))
        <p class="validation-error text-danger">{{$errors->first('client_id')}}</p>
        @endif
      </div>

      <div class="mb-3">
        <label for="project_name" class="form-label">Project Name</label>
        <input type="text" class="form-control" id="project_name" name="project_name" value="{{ !empty($data) ? $data['project_name'] : ''}}" required autocomplete="off">
        @if($errors->has('project_name'))
        <p class="validation-error text-danger">{{$errors->first('project_name')}}</p>
        @endif
      </div>

      <div class="mb-3">
        <label for="project_description" class="form-label">Project Description</label>
        <textarea class="form-control" name="project_description" id="project_description" rows="5" autocomplete="off">@if(!empty($data)){{$data['project_description']}}@endif</textarea>
      </div>

      <div class="row">
        <div class="col-md-5 mb-3">
          <label for="order_value" class="form-label">Order Value</label>
          <input type="text" class="form-control" id="order_value" name="order_value" value="{{ !empty($data) ? $data['order_value'] : ''}}" required autocomplete="off" onkeypress="return isNumber(event)">
          @if($errors->has('order_value'))
          <p class="validation-error text-danger">{{$errors->first('order_value')}}</p>
          @endif
        </div>

        <div class="col-md-5 mb-3">
          <label for="collected_till" class="form-label">Collected Till</label>
          <input type="text" class="form-control" id="collected_till" name="collected_till" value="{{ !empty($data) ? $data['collected_till'] : ''}}" autocomplete="off" onkeypress="return isNumber(event)">
        </div>

        <div class="col-md-2 mb-3">
          <label for="billable" class="form-check-label">Is Billable</label><br>
          <input class="form-check-input mt-3 billable-check" type="checkbox" id="billable" name="billable" @if(!empty($data) && $data['is_billable']=="yes" ) checked @endif>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="start_date" class="form-label">Work Start Date</label>
          <input type="text" class="form-control datepicker" id="start_date" name="start_date" value="{{ !empty($data) ? date('d-m-Y',strtotime($data['work_start_date'])) : ''}}" required autocomplete="off">
          @if($errors->has('end_date'))
          <p class="validation-error text-danger">{{$errors->first('start_date')}}</p>
          @endif
        </div>

        <div class="col-md-6 mb-3">
          <label for="end_date" class="form-label">Work End Date</label>
          <input type="text" class="form-control datepicker" id="end_date" name="end_date" value="{{ !empty($data) ? date('d-m-Y',strtotime($data['work_end_date']))  : ''}}" required autocomplete="off">
          @if($errors->has('end_date'))
          <p class="validation-error text-danger">{{$errors->first('end_date')}}</p>
          @endif
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label for="project-status" class="form-label">status</label>
          <select name="status" id="project-status" class="select-box form-control">
            <option value="">Select Status</option>
            <option value="yet_to_start" @if(!empty($data) && $data['work_status']=="yet_to_start" ) selected @endif>Yet To Start</option>
            <option value="work_in_progress" @if(!empty($data) && $data['work_status']=="work_in_progress" ) selected @endif>Work In Progress</option>
            <option value="completed" @if(!empty($data) && $data['work_status']=="completed" ) selected @endif>Completed</option>
            <option value="hold" @if(!empty($data) && $data['work_status']=="hold" ) selected @endif>Hold</option>
          </select>
        </div>

        <div class="col-md-6">
          <label for="actual_delivery_date" class="form-label">Actual Delivery Date</label>
          <input class="form-control" type="text" name="actual_delivery_date" id="actual_delivery_date" value="{{ (!empty($data) && !is_null($data['actual_delivery_date'])) ? date('d-m-Y',strtotime($data['actual_delivery_date'])) : '' }}" readonly>
        </div>
      </div>

      <div class="mb-3">
        <label for="status_remarks" class="form-label">Status Remarks</label>
        <textarea class="form-control" name="status_remarks" id="status_remarks" rows="5">@if(!empty($data)){{$data['work_status_remarks']}}@endif</textarea>
      </div>


      <div class="mb-3 employee_assign-div">
        <label class="form-label mb-4">Project Assign</label>
        <input type="hidden" id="total_employee_assign_count" name="total_employee_assign_count" value="{{  !empty($data) ? $data->project_assign_details_count : '' }}">
        @if(!empty($data) && count($data->project_assign_details) > 0)
        @foreach($data->project_assign_details as $k => $val)
        <div class="row employee_assign-row mb-2 @if($k+1 > 1)assign-{{$k+1}}@endif">
          <input type="hidden" name="emp_project_assign_id{{$k+1}}" value="{{ $val->id }}">
          <div class="col-md-1">
            <label for="sno" class="form-label">Sno</label>
            <input type="text" class="form-control" id="sno" value="{{ $k+1 }}">
          </div>
          <div class="col-md-4">
            <label for="employee_name" class="form-label">Employee</label>
            <select id="employee_id" name="employee_id{{$k+1}}" class="select-box form-control employee{{$k+1}} employee_id @if($data->project_assign_details->count() != $k+1) pointer-none @endif">
              <option value="">Select Employee</option>
              @foreach($employees as $key => $value)
              <option value="{{ $value->id }}" @if(!empty($val) && $val['employee_id']==$value->id) selected @endif>{{ $value->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2">
            <label for="hours" class="form-label">Hours</label>
            <input type="text" class="form-control hours{{$k+1}}" id="hours" name="hours{{$k+1}}" value="@if(!empty($val)){{ $val['hours'] }}@endif" autocomplete="off" onkeypress="return isNumber(event)">
          </div>
          <div class="col-md-4">
            <label for="description" class="form-label">Description</label>
            <input type="text" class="form-control description{{$k+1}}" id="description" name="description{{$k+1}}" value="@if(!empty($val)){{ $val['description'] }}@endif" autocomplete="off">
          </div>
          @if($k+1 > 1)
          <div class='col-md-1 mt-4'>
            <button type="button" class="btn btn-danger employee-remove removemember{{$k+1}}" data-row="{{$k+1}}" data-id="{{ $val->id }}">
              <i class="fa fa-trash"></i>
            </button>
          </div>
          @endif
        </div>
        @endforeach
        @else
        <div class="row employee_assign-row mb-2">
          <input type="hidden" name="emp_project_assign_id1" value="0">
          <div class="col-md-1">
            <label for="sno" class="form-label">Sno</label>
            <input type="text" class="form-control" id="sno" value="1">
          </div>
          <div class="col-md-4">
            <label for="employee_name" class="form-label">Employee</label>
            <select id="employee_id" name="employee_id1" class="select-box form-control employee1">
              <option value="">Select Employee</option>
              @foreach($employees as $key => $value)
              <option value="{{ $value->id }}">{{ $value->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2">
            <label for="hours" class="form-label">Hours</label>
            <input type="text" class="form-control hours1" id="hours" name="hours1" autocomplete="off" onkeypress="return isNumber(event)">
          </div>
          <div class="col-md-4">
            <label for="description" class="form-label">Description</label>
            <input type="text" class="form-control description1" id="description" name="description1" autocomplete="off">
          </div>
          <!-- <div class="col-md-2 mt-4">
            <button type="button" class="btn btn-success add-employee-btn addmember1">Add Member</button>
          </div> -->
        </div>
        @endif
      </div>
      <div class="col-md-2 mb-5">
            <button type="button" class="btn btn-success add-employee-btn addmember1"><i class="fa fa-user" aria-hidden="true"></i> Add Member</button>
      </div>

      <div class="row mb-3">
        <h5 class="form-label mb-4">Documents</h5>

        <!------ client PO/SO docs start ----------->
        <div class="card-head fw-bold">Client PO/SO</div>
        <div class="card mt-3">
          <div class="card-body">
            <div>
              <div class="row">
                <div class="mb-3 col-md-4 col-lg-4">
                  <label for="po">Document</label>
                  @if(!empty($data) && !is_null($data['client_po_doc_name']))
                  <input type="hidden" class="form-control" name="client_po_doc" value="{{ $data['client_po_doc_name'] }}">
                  @endif
                  <input type="file" class="form-control file" name="client_po_doc">
                  @if(!empty($data) && !is_null($data['client_po_doc_name']))
                  <a class="choosed-file" href="{{ URL::to('/storage/app/public/client_po_docs/'.$data['client_po_doc_name']) }}"><i class="fa fa-download" aria-hidden="true"></i>{{ $data['client_po_doc_name']}}</a>
                  @else
                  <a class="choosed-file"></a>
                  @endif
                </div>

                <div class="mb-3 col-lg-8 col-md-8">
                  <label for="description">Description</label>
                  <textarea id="description" class="form-control" name="client_po_description">{{ !empty($data) ? $data['client_po_description'] : ''}}</textarea>
                </div>
              </div>

            </div>
            @if($errors->has('client_po_doc'))
            <p class="validation-error text-danger">{{$errors->first('client_po_doc')}}</p>
            @endif
          </div>
        </div>
        <!------ client PO/SO docs end ----------->

        <!------ Proposal docs start ----------->
        <div class="card-head fw-bold">Proposal</div>
        <div class="card mt-3">
          <div class="card-body">
            <div>
              <div class="row">
                <div class="mb-3 col-md-4 col-lg-4">
                  <label for="po">Document</label>
                  @if(!empty($data) && !is_null($data['proposal_doc_name']))
                  <input type="hidden" class="form-control" name="proposal_doc" value="{{ $data['proposal_doc_name'] }}">
                  @endif
                  <input type="file" class="form-control file" name="proposal_doc">
                  @if(!empty($data) && !is_null($data['proposal_doc_name']))
                  <a class="choosed-file" href="{{ URL::to('/storage/app/public/proposal_docs/'.$data['proposal_doc_name']) }}"><i class="fa fa-download" aria-hidden="true"></i>{{ $data['proposal_doc_name']}}</a>
                  @else
                  <a class="choosed-file"></a>
                  @endif
                </div>

                <div class="mb-3 col-lg-8 col-md-8">
                  <label for="description">Description</label>
                  <textarea id="description" class="form-control" name="proposal_description">{{ !empty($data) ? $data['proposal_description'] : ''}}</textarea>
                </div>
              </div>

            </div>
            @if($errors->has('proposal_doc'))
            <p class="validation-error text-danger">{{$errors->first('proposal_doc')}}</p>
            @endif
          </div>
        </div>
        <!------ Proposal docs End ----------->

        <!------ Work Order docs start ----------->
        <div class="card-head fw-bold">Work Order</div>
        <div class="card mt-3">
          <div class="card-body">
            <div>
              <div class="row">
                <div class="mb-3 col-md-4 col-lg-4">
                  <label for="po">Document</label>
                  @if(!empty($data) && !is_null($data['work_order_doc_name']))
                  <input type="hidden" class="form-control" name="work_order_doc" value="{{ $data['work_order_doc_name'] }}">
                  @endif
                  <input type="file" class="form-control file" name="work_order_doc">
                  @if(!empty($data) && !is_null($data['work_order_doc_name']))
                  <a class="choosed-file" href="{{ URL::to('/storage/app/public/work_order_docs/'.$data['work_order_doc_name']) }}"><i class="fa fa-download" aria-hidden="true"></i>{{ $data['work_order_doc_name']}}</a>
                  @else
                  <a class="choosed-file"></a>
                  @endif
                </div>

                <div class="mb-3 col-lg-8 col-md-8">
                  <label for="description">Description</label>
                  <textarea id="description" class="form-control" name="work_order_description">{{ !empty($data) ? $data['work_order_description'] : ''}}</textarea>
                </div>
              </div>

            </div>
            @if($errors->has('work_order_doc'))
            <p class="validation-error text-danger">{{$errors->first('work_order_doc')}}</p>
            @endif
          </div>
        </div>
        <!------ Work Order docs end ----------->

        <!------ Other docs start ----------->
        @if(!empty($data) && count($data->otherdocuments) > 0)
        <input type="hidden" id="doc_section_count" value="{{ count($data->otherdocuments) }}">
        <div class="card-head fw-bold">Other Documents</div>
        <div class="card mt-3">
          <div class="card-body others_card_body">
            @foreach($data->otherdocuments as $dkey => $dval)
            <input type="hidden" name="po_id[]" value="{{$dval->id}}">
            <div class="po_detail_{{ $dkey+1 }}">
              <div class="row">
                <div class="mb-3 col-md-4 col-lg-4">
                  <label for="po">Document {{ $dkey+1 }}</label>
                  <input type="file" class="form-control file" id="po" name="po_documents[]">
                  <input type="hidden" class="form-control po_documents{{ $dkey }}" id="po" name="po_documents[]" value="{{ $dval->other_docs_name }}">

                  <a class="choosed-file" href="{{ URL::to('/storage/app/public/project_other_docs/'.$dval->other_docs_name) }}"><i class="fa fa-download" aria-hidden="true"></i>{{ $dval->other_docs_name }}</a>
                </div>

                <div class="mb-3 col-lg-6 col-md-6">
                  <label for="description">Description</label>
                  <textarea id="description" class="form-control" name="description[]">{{ $dval->other_docs_description }}</textarea>
                </div>

                <div class="col-lg-2 align-self-center col-md-2">
                  <div class="d-grid">
                    <button type="button" data-section="{{ $dkey+1 }}" data-po-id="{{ $dval->id }}" class="btn btn-danger delete_section"><i class="fa-solid fa-trash-can"></i> Delete</button>
                  </div>
                </div>
              </div>

            </div>
            @endforeach
          </div>
          <button type="button" class="btn btn-success col-md-2 mb-3 add-po-section"><i class="fa-solid fa-plus"></i> Add</button>
        </div>
        @else
        <input type="hidden" id="doc_section_count" value="1">
        <div class="card-head fw-bold">Other Documents</div>
        <div class="card mt-3">
          <div class="card-body others_card_body">
            <div class="po_detail_1">
              <div class="row">
                <div class="mb-3 col-md-4 col-lg-4">
                  <label for="po">Document 1</label>
                  <input type="file" class="form-control po_documents_file" id="po" name="po_documents[]">
                  <a class="choosed-file"></a>
                </div>

                <div class="mb-3 col-lg-6 col-md-6">
                  <label for="description">Description</label>
                  <textarea id="description" class="form-control" name="description[]"></textarea>
                </div>

                <div class="col-lg-2 align-self-center col-md-2">
                  <div class="d-grid">
                    <button type="button" data-section="1" class="btn btn-danger delete_section"><i class="fa-solid fa-trash-can"></i> Delete</button>
                  </div>
                </div>
              </div>
            </div>

          </div>
          <button type="button" class="btn btn-success col-md-2 mb-3 add-po-section"><i class="fa-solid fa-plus"></i> Add</button>
        </div>
        @endif
        <!------ Other docs End ----------->

      </div>

      <div class="d-flex justify-content-end me-4 mb-5">
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </form>
  </div>
  <div class="col-md-2">
  </div>
</div>
<script>
  var base_path = '<?php echo url('/'); ?>';
  var token = $('meta[name="csrf-token"]').attr('content');

  $(function() {
    $('.select-box').select2({
      width: '100%'
    });
  });
  $(document).on('click', '.add-employee-btn', function() {

    var count = parseInt($('.employee_assign-row').length) + 1;

    $html = "<div class='row employee_assign-row mb-2 assign-" + count + "'><input type='hidden' name='emp_project_assign_id" + count + "' value='0'><div class='col-md-1'><label for='sno' class='form-label'>Sno</label><input type=text class='form-control' id='sno' value='" + count + "'></div>";
    $html += "<div class='col-md-4'><label for='employee_name' class='form-label'>Employee</label><select id='employee_id' name='employee_id" + count + "' class='select-box form-control employee" + count + "'><option value= selected>Select Employee</option>@foreach($employees as $key => $value) <option value={{ $value->id }} class='emp{{$value->id}}'>{{ $value->name }}</option>@endforeach</select></div>";
    $html += "<div class='col-md-2'><label for='hours' class='form-label'>Hours</label><input type=text class='form-control hours" + count + "'id='hours' name='hours" + count + "' onkeypress='return isNumber(event)' autocomplete='off'></div>";
    $html += "<div class='col-md-4'><label for='description' class='form-label'>Description</label><input type='text' class='form-control description" + count + "' id='description' name='description" + count + "' value='' autocomplete='off'></div>";
    $html += "<div class='col-md-1 mt-4'><button type='button' class='btn btn-danger employee-remove removemember" + count + "' data-row='" + count + "'><i class='fa fa-trash'></i></button></div></div>";

    $('.employee_assign-div').append($html);
    $('#total_employee_assign_count').val(count);
    $('.select-box').select2({
      width: '100%'
    });

  });

  $(document).on('click', '.employee-remove', function() {
    var base_path = '<?php echo url('/'); ?>';
    var token = $('meta[name="csrf-token"]').attr('content');
    var row_id = $(this).data('row');
    var project_assign_id = $(this).data('id');
    var previous_id = (row_id > 1) ? (parseInt(row_id) - parseInt(1)) : row_id;
    $('.assign-' + row_id).remove();
    $('.employee' + previous_id).css('pointer-events', '');
    $('.employee' + previous_id).removeClass('pointer-none');


    var count = parseInt($('.employee_assign-row').length) - 1;

    $('#total_employee_assign_count').val(count);


    if (project_assign_id) {
      $.ajax({
        url: base_path + "/project_member_delete",
        type: "DELETE",
        data: {
          _token: token,
          id: project_assign_id
        },
        success: function() {

        }
      });
    }
    $('.select-box').select2({
      width: '100%'
    });
  });

  $(document).on('click', '#client_btn', function() {
    var base_path = '<?php echo url('/'); ?>';
    var client_name = $('#client').val();
    var token = $("meta[name=csrf-token]").attr('content');
    $.ajax({
      url: base_path + "/client_add",
      type: "POST",
      data: {
        _token: token,
        client_name: client_name
      },
      success: function(res) {
        if (res.status == 200) {
          window.location.reload();
        }
      },
      error: function(res) {
        $('.client_creation_err').text(JSON.parse(res.responseText).msg).fadeIn();
      }
    });
  });

  $('#project-status').on('change', function() {
    $('#actual_delivery_date').val('');
    var selected_value = $(this).find(':selected').val();
    var today = "<?php echo date('d-m-Y'); ?>";
    if (selected_value == 'completed') {
      $('#actual_delivery_date').val(today);
    }
  });

  $(document).on('change', '.file', function() {
    $filename = $(this).val().split('\\').pop();
    $(this).next('.choosed-file').text($filename);
  });

  $(document).on('click', '.add-po-section', function() {
    var section_count = parseInt($('#doc_section_count').val()) + 1;
    $('#doc_section_count').val(section_count);
    var html = `<div class="po_detail_` + section_count + `">
            <div class="row">
              <div class="mb-3 col-md-4 col-lg-4">
                <label for="po">Document ` + section_count + `</label>
                <input type="file" class="form-control po_documents_file" id="po" name="po_documents[]">
                <a class="choosed-file"></a>
              </div>

              <div class="mb-3 col-lg-6 col-md-6">
                <label for="description">Description</label>
                <textarea id="description" class="form-control" name="description[]"></textarea>
              </div>

              <div class="col-lg-2 align-self-center col-md-2">
                <div class="d-grid">
                  <button  type="button" data-section="` + section_count + `" class="btn btn-danger delete_section"><i class="fa-solid fa-trash-can"></i> Delete</button>
                </div>
              </div>
            </div>
          </div>`;
    $('.others_card_body').append(html).slideDown('slow').delay(1500);
  });

  $(document).on('click', '.delete_section', function() {
    var remove_section_id = $(this).data('section');
    var po_id = $(this).data('po-id');
    var section_cnt = parseInt($('#doc_section_count').val()) - 1;
    $('#doc_section_count').val(section_cnt);
    if (remove_section_id) {
      $.ajax({
        url: base_path + "/other_docs_remove",
        type: "DELETE",
        data: {
          id: po_id,
          _token: token
        },
        success: function(res) {}
      });
    }
    $('.po_detail_' + remove_section_id).remove();
  });
  
 $(document).on('focusout','#order_value,#collected_till',function(){
    var float_val = parseFloat($(this).val()).toFixed(2);
    $(this).val(float_val);
  }); 
</script>
@endsection