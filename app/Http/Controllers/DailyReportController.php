<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use DataTables;
use App\Models\Client;
use App\Models\ProjectAssignDetails;
use App\Models\User;
use App\Models\Project;

class DailyReportController extends Controller
{
    public $method;
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->method = $request->method();
    }
    public function index(Request $request)
    {
        $client_id   = isset($request->client_id) ? $request->client_id : ''; 
        $project_id  = isset($request->project_id) ? $request->project_id : ''; 
        $employee_id = isset($request->employee_id) ? $request->employee_id : ''; 
        $work_status = (isset($request->status) && $request->status != 'all') ? $request->status : ''; 
        $start_date  = isset($request->start_date) ? date('Y-m-d',strtotime($request->start_date)) : '';
        $end_date    = isset($request->end_date) ? date('Y-m-d',strtotime($request->end_date)) : '';


        $clients    = Client::get();
        $projects   = Project::where('delete_status','!=',1);
        if(role_name() == "Employee") {
            $projects = ProjectAssignDetails::with('project_detail')->where('employee_id',\Auth::user()->id);
        }
        $projects   = $projects->get();
        $employees  = User::where('status','Active')->get();        
        
        $reports    = Task::where('delete_status','!=',1)->where('employee_id',\Auth::user()->id); 
        if(role_name() == "Admin") {
            $reports = Task::query();
            if($employee_id) {
                $reports->where('employee_id',$employee_id);
            }
        }
        if($client_id) {
            $reports->where('client_id',$client_id);
        }
        if($project_id) {
            $reports->where('project_id',$project_id);
        }
        if($work_status) {
            $reports->where('status',$work_status);
        }
        if($start_date) {
            $reports->where('date','>=',$start_date);
        }
        if($end_date) {
            $reports->where('date','<=',$end_date);
        }
        $reports = $reports->orderBy('id','desc')->get();

        $total_minutes_taken = $reports->sum('time_taken');

        $work_status = ($request->status == "all") ? "all" : $work_status; 

        
        return view('daily_report.index',compact('clients','projects','employees', 'reports','client_id','project_id','employee_id','work_status','total_minutes_taken','start_date','end_date'));
    }
}
