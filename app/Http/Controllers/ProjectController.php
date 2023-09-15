<?php

namespace App\Http\Controllers;

use App\Mail\ChangesCaptured;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use DataTables;
use Validator;
use App\Models\Client;
use App\Models\User;
use App\Models\ProjectOtherDocuments;
use App\Models\ProjectAssignDetails;

class ProjectController extends Controller
{
    public $method;
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->method = $request->method();
    }

    public function index(Request $request)
    {
        $projects = Project::where('delete_status', "!=", 1)->get();

        if ($request->ajax()) {
            return Datatables::of($projects)->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex actions-div">
                    <a href="javascript:void(0)" style="width:60px;height: 30px;" class="btn btn-info btn-sm view-btn" data-id="' . $row->id . '"><i class="fa fa-eye" aria-hidden="true"></i> view</a>
                    <a href="javascript:void(0)" style="width:60px;height: 30px;" class="btn btn-primary btn-sm edit-btn ms-3" data-id="' . $row->id . '"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a>
                    <a href="javascript:void(0)" style="width:70px;height: 30px;" class="btn btn-danger btn-sm delete-btn ms-3" data-id="' . $row->id . '"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
                    return $btn;
                })->addColumn('client_name', function ($project) {
                    return !is_null($project->client_name) ? $project->client_name->name : '';
                })->addColumn('work_s_d', function ($project) {
                    return date('d-m-Y', strtotime($project->work_start_date));
                })->addColumn('work_e_d', function ($project) {
                    return date('d-m-Y', strtotime($project->work_end_date));
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('project.index');
    }

    public function view(Request $request)
    {
        $data = [];
        $clients   = Client::get();
        $employees = User::get();
        if (isset($request->id) && !empty($request->id)) {
            $data = Project::with(['project_assign_details', 'otherdocuments'])->withCount('project_assign_details')->find($request->id);
        }
        return view('project.form', compact('data', 'clients', 'employees'));
    }

    public function store(Request $request)
    {
        $status = "failed";
        $msg = "Something went wrong.";
        $project_assign_count = ($request->total_employee_assign_count > 0) ? $request->total_employee_assign_count : 1;
        $po_det_count = !is_null($request->po_documents) ? count($request->po_documents) : 0;

        // dd($request->all());
        if ($request->id == '') {
            $val_arr = [
                'project_name' => 'required',
                'client_id'  => 'required',
                'query_no'  => 'unique:projects,query_no',
                'work_order' => 'unique:projects,work_order_no|required',
                'order_value' => 'required|numeric',
                'start_date' => 'required',
                'end_date' => 'required',
                'status' => 'required',
                'client_po_doc' => 'required',
                'proposal_doc' => 'required',
                'work_order_doc' => 'required'
            ];
        } else {
            $val_arr = [
                'project_name' => 'required',
                'client_id'  => 'required',
                'order_value' => 'required|numeric',
                'start_date' => 'required',
                'end_date' => 'required',
                'status' => 'required',
                'client_po_doc' => 'required',
                'proposal_doc' => 'required',
                'work_order_doc' => 'required',
                'work_order' => 'required',
            ];
        }
        $validator = Validator::make($request->all(), $val_arr);

        if ($validator->passes()) {
            if ($this->method == "PATCH") {
                $project = Project::find($request->id);
            } else {
                $project = new Project;
            }
            $project->query_no             = !is_null($request->query_no) ? $request->query_no : '';
            $project->work_order_no        = !is_null($request->work_order) ? $request->work_order : '';
            $project->client_id            = $request->client_id;
            $project->project_name         = $request->project_name;
            $project->project_description  = $request->project_description;
            $project->order_value          = $request->order_value;
            $project->collected_till       = $request->collected_till;
            $project->work_start_date      = date('Y-m-d', strtotime($request->start_date));
            $project->work_end_date        = date('Y-m-d', strtotime($request->end_date));
            $project->actual_delivery_date = !is_null($request->actual_delivery_date) ? date('Y-m-d', strtotime($request->actual_delivery_date)) : NULL;
            $project->is_billable          = ($request->billable == "on") ? "yes" : "no";
            $project->work_status          = $request->status;
            $project->work_status_remarks  = !is_null($request->status_remarks) ? $request->status_remarks : '';
            $project->delete_status        = 0;

            if ($request->hasfile('client_po_doc')) {
                if ($this->method == "PATCH") {
                    if (!is_null($project->client_po_doc_name) && file_exists('storage/app/public/client_po_docs/' . $project->client_po_doc_name)) {
                        unlink('storage/app/public/client_po_docs/' . $project->client_po_doc_name);
                    }
                }
                $po_file_name = $request->project_name . "_po_document_" . time() . "." . $request->client_po_doc->extension();
                $request->client_po_doc->storeAs('public/client_po_docs', $po_file_name);
                $project->client_po_doc_name      = $po_file_name;
            } else {
                $project->client_po_doc_name      = $request->client_po_doc;
            }

            if ($request->hasfile('proposal_doc')) {
                if ($this->method == "PATCH") {
                    if (!is_null($project->proposal_doc_name) && file_exists('storage/app/public/proposal_docs/' . $project->proposal_doc_name)) {
                        unlink('storage/app/public/proposal_docs/' . $project->proposal_doc_name);
                    }
                }
                $proposal_file_name = $request->project_name . "_proposal_document_" . time() . "." . $request->proposal_doc->extension();
                $request->proposal_doc->storeAs('public/proposal_docs', $proposal_file_name);
                $project->proposal_doc_name      = $proposal_file_name;
            } else {
                $project->proposal_doc_name      = $request->proposal_doc;
            }

            if ($request->hasfile('work_order_doc')) {
                if ($this->method == "PATCH") {
                    if (!is_null($project->work_order_doc_name) && file_exists('storage/app/public/work_order_docs/' . $project->work_order_doc_name)) {
                        unlink('storage/app/public/work_order_docs/' . $project->work_order_doc_name);
                    }
                }
                $work_order_file_name = $request->project_name . "_work_order_document_" . time() . "." . $request->work_order_doc->extension();
                $request->work_order_doc->storeAs('public/work_order_docs', $work_order_file_name);
                $project->work_order_doc_name     = $work_order_file_name;
            } else {
                $project->work_order_doc_name     = $request->work_order_doc;
            }

            $project->client_po_description   = $request->client_po_description;
            $project->proposal_description    = $request->proposal_description;
            $project->work_order_description  = $request->work_order_description;
            if ($request->id) {
             $original_data                     = $project->getOriginal();
             $original_data['client_name']      = Client::find($original_data['client_id'])->name;
             $original_data['estimation_hours'] = ProjectAssignDetails::where('project_id', $original_data['id'])->get()->sum('hours');
            }
            $save = $project->save();

            if ($save) {
                if ($request->id) {
                    $assigned_project_client = ProjectAssignDetails::where('project_id', $request->id)->get();
                    $task_project_client     = Task::where('project_id', $request->id)->get(); 
                    if ($assigned_project_client) {
                        foreach ($assigned_project_client as $ck => $cval) {
                            $cval->client_id = $request->client_id;
                            $cval->save();
                        }
                    }
                    if($task_project_client) {
                        foreach ($task_project_client as $tk => $tval) {
                            $tval->client_id = $request->client_id;
                            $tval->save();
                        }
                    }
                }   
                if ($this->method == "PATCH") {
                    $changed_data = $project;
                    $to_mail = [\Auth::user()->email];
                    $cc_mail = [
                        'productivity@mazenetsolution.com',
                        'dharmaraj@mazenettech.com',
                        'ashokraj@mazenetsolution.com'
                    ];
                    $cc_mail = [];
                    \Mail::to($to_mail)->cc($cc_mail)->send(new ChangesCaptured($original_data,$changed_data));
                    // return view('automation.changes_captured', compact('changed_data', 'original_data'));
                }
                for ($i = 1; $i <= $project_assign_count; $i++) {
                    if (!is_null($request['employee_id' . $i]) && !is_null($request['hours' . $i])) {
                        $a = ProjectAssignDetails::updateOrCreate(
                            [
                                'project_id'  => $project->id,
                                'client_id'   => $request->client_id,
                                'id'          => $request['emp_project_assign_id' . $i]
                            ],
                            [
                                'employee_id' => $request['employee_id' . $i],
                                'hours'       => $request['hours' . $i],
                                'description' => $request['description' . $i]
                            ]
                        );
                    }
                }


                if ($po_det_count > 0) {
                    $edit_count  = isset($request->po_id) ? count($request->po_id) : 0;
                    $edit_action = ($edit_count > 0) ? true : false;
                    for ($i = 0; $i < $po_det_count; $i++) {
                        $other_documents = new ProjectOtherDocuments;
                        if ($edit_action) {
                            $other_documents = ProjectOtherDocuments::find($request->po_id[$i]);
                        }
                        if (isset($request->po_documents[$i]) && file_exists($request->po_documents[$i])) {
                            if ($edit_action) {
                                if (file_exists("storage/app/public/project_other_docs/" . $other_documents->other_docs_name)) {
                                    unlink("storage/app/public/project_other_docs/" . $other_documents->other_docs_name);
                                }
                            }
                            $file_name   = $request->project_name . "_other_document_" . $i + 1 . time() . "." . $request->po_documents[$i]->extension();
                            $file_path   = $request->po_documents[$i]->getPathName();
                            $upload_path = "storage/app/public/project_other_docs/" . $file_name;
                            move_uploaded_file($file_path, $upload_path);
                        } else {
                            $file_name =  $request->po_documents[$i];
                        }

                        $other_documents->project_id             = $project->id;
                        $other_documents->other_docs_name        = $file_name;
                        $other_documents->other_docs_description = $request->description[$i];
                        $other_documents->save();
                        if ($edit_count == $i + 1) {
                            $edit_action = false;
                        }
                    }
                }
                $msg = "Project details updated successfully.";
                $status = "success";
            }
            return redirect('/project')->with($status, $msg);
        } elseif ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
    }

    public function remark(Request $request)
    {
        $project_id = $request->id;
        return view('project.remark', compact('project_id'));
    }

    public function destroy(Request $request)
    {
        $project = Project::find($request->id);
        $project->delete_status          = 1;
        $project->delete_remarks       = $request->remark;
        $save = $project->save();
        if ($save) {
            ProjectAssignDetails::where('project_id', $request->id)->delete();
            $status = "success";
            $msg = "Project details deleted successfully.";
        }
        return redirect('/project')->with($status, $msg);
    }

    public function project_member_remove(Request $request)
    {
        $data   = ProjectAssignDetails::find($request->id);
        $delete = $data->delete();
        if ($delete) {
            $status = "success";
            $msg    = "Project members deleted successfully.";
        }
    }

    public function project_view(Request $request)
    {
        $data['message'] = "Unproccessable Entry";
        $status          = 422;
        $data['project_det'] = [];
        $project              = Project::with(['project_assign_details.employee_detail', 'task'])->find($request->id)->append(['estimation_hours', 'actual_delivery_date_format']);
        if ($project) {
            $data['message']     = "project details fetched successfully.";
            $status              = 200;
            $data['project_det'] = $project;
        }
        return response()->json($data, $status);
    }

    public function other_docs_remove(Request $request)
    {
        if (request()->ajax()) {
            $msg = "Something went wrong.";
            $doc_details = ProjectOtherDocuments::find($request->id);
            if ($doc_details) {
                if (file_exists('storage/app/public/project_other_docs/' . $doc_details->other_docs_name)) {
                    unlink('storage/app/public/project_other_docs/' . $doc_details->other_docs_name);
                }
                $doc_details->delete();
                $msg = "Other document detail deleted successfully.";
            }
            return response()->json($msg);
        }
    }
}
