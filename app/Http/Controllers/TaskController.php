<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Client;
use App\Models\Project;
use App\Models\ProjectAssignDetails;
use PhpParser\Node\Expr\Cast\Object_;
use stdClass;
use Validator, DataTables;
use Illuminate\Support\Collection;

class TaskController extends Controller
{
    public $method;

    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->method = $request->method();
    }

    public function index(Request $request)
    {
        $tasks = Task::where('delete_status', "!=", 1);
        if(\Auth::user()->role == 2) {
            $tasks = $tasks->where('employee_id',\Auth::user()->id);
        }
        $tasks = $tasks->orderBy('id','desc')->get();
        if ($request->ajax()) {
            return Datatables::of($tasks)->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex actions-div"><a href="javascript:void(0)" class="btn btn-primary btn-sm edit-btn" data-id="' . $row->id . '" style="width:60px;height:30px;"><i class="fa fa-edit" aria-hidden="true"></i>  Edit</a><a href="javascript:void(0)" class="btn btn-danger btn-sm delete-btn ms-3" data-id="' . $row->id . '" style="width:70px;height:30px;"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
                    return $btn;
                })->addcolumn('client_name',function($tasks){
                    return !is_null($tasks->client_detail) ? $tasks->client_detail->name : ''; 
                })->addcolumn('project_name',function($tasks){
                    return !is_null($tasks->project_detail) ? $tasks->project_detail->project_name : ''; 
                })->addcolumn('man_hours',function($tasks){
                    return minutes_to_hour($tasks->time_taken); 
                })->addcolumn('task_date',function($tasks){
                    return date('d-m-Y',strtotime($tasks->date)); 
                })
                ->make(true);
        }
        return view('task.index');
    }

    public function view(Request $request)
    {
        $data = [];
        $time_taken_hours   = '';
        $time_taken_minutes = '';
        $assigned_projects_client   = ProjectAssignDetails::select(\DB::raw('GROUP_CONCAT(client_id) as client_id,GROUP_CONCAT(project_id) as project_id'))->where('employee_id',\Auth::user()->id)->get()->toArray();
        $client_arr = array_unique(explode(',',$assigned_projects_client[0]['client_id']));
        $project_arr = array_unique(explode(',',$assigned_projects_client[0]['project_id'])); 
        $clients    = Client::whereIn('id',$client_arr)->get();
        $projects   = Project::whereIn('id',$project_arr)->get();
        if(\Auth::user()->role == 1) {
            $clients   = Client::get();
            $projects  = Project::get();
        } 
        if (isset($request->id) && !empty($request->id)) {
            $data               = Task::find($request->id);
            $total_minutes      = $data->time_taken;
            $time_taken_hours   = floor($total_minutes / 60);
            $time_taken_minutes = $total_minutes - ($time_taken_hours * 60);

            $time_taken_hours     = time_format_change($time_taken_hours);
            $time_taken_minutes   = time_format_change($time_taken_minutes);
        }
        return view('task.form', compact('data', 'time_taken_hours', 'time_taken_minutes','clients','projects'));
    }

    public function store(Request $request)
    {
        $status = "failed";
        $msg = "Something went wrong.";
        $validation_msg = [
            'time_taken_hour.gt' => "Task Hours and Minutes cannot be zero.",
            'time_taken_minutes.gte' => "Task time should be greater than or equal to 30 minutes"
        ];
        $validation_arr = [
            'date' => 'required',
            'client_id' => 'required',
            'project_id' => 'required',
            'task_name' => 'required',
            'status' => 'required'
        ]; 
        if($request->time_taken_hour == 00 && $request->time_taken_minutes == 00) {
            $validation_arr['time_taken_hour'] = 'gt:0';
        } elseif($request->time_taken_hour == 00 && $request->time_taken_minutes < 30) {
            $validation_arr['time_taken_minutes'] = 'gte:30';
        }
        $validator = Validator::make($request->all(), $validation_arr,$validation_msg);
        if ($validator->passes()) {
            $time_taken_minutes = (int) $request->time_taken_hour * 60 + (int) $request->time_taken_minutes;
            if ($this->method == "PATCH") {
                $task = Task::find($request->id);
                $from = "update";
            } else {
                $task = new Task;
                $from = "create";
            }
            $task->date             = date('Y-m-d',strtotime($request->date));
            $task->client_id        = $request->client_id;
            $task->project_id       = $request->project_id;
            if(!isset($request->id)) {
                $task->employee_id      = \Auth::user()->id;
            }
            $task->task_name        = $request->task_name;
            $task->task_description = !is_null($request->task_description) ? $request->task_description : '';
            $task->time_taken       = $time_taken_minutes;
            $task->status           = $request->status;
            $task->status_remarks   = !is_null($request->status_remarks) ? $request->status_remarks : '';
            $task->delete_status    = 0;
            $task->updated_by       = \Auth::user()->id;


            $save = $task->save();
            if ($save) {
                $action = ($from == "create") ? "added" : "updated";
                $msg = "Man hours details " . $action . " successfully.";
                $status = "success";
            }
            return redirect('/task')->with($status, $msg);
        } elseif ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
    }

    public function remark(Request $request)
    {
        $task_id = $request->id;
        return view('task.remark',compact('task_id'));
    }

    public function destroy(Request $request)
    {
        $task = Task::find($request->id);
        $task->delete_status         = 1;
        $task->delete_remarks = $request->remark;
        $save = $task->save();
        if($save) {
            $status = "success";
            $msg = "Man hours details deleted successfully.";
        }
        return redirect('/task')->with($status, $msg);
    }

    public function assigned_project_get(Request $request)
    {
        $projects   = Project::where('client_id',$request->client_id)->get();
        if(\Auth::user()->role == 2) {
            $projects = new Collection;
            $assigned_projects   = ProjectAssignDetails::with('project_detail')->where('client_id',$request->client_id)->where('employee_id',\Auth::user()->id)->get();
            foreach($assigned_projects as $key => $value) {
                $project                = Project::where('id',$value->project_id)->first();
                $projects->push($project);
            }
        }
        $data['role']     = \Auth::user()->role;
        $data['projects'] = $projects;
        return $data;
    }
}
