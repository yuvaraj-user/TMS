<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use DataTables;
use App\Models\Client;
use App\Models\ProjectAssignDetails;
use App\Models\User;

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
        // dd($request->all());
        $client_id   = isset($request->client_id) ? $request->client_id : ''; 
        $project_id  = isset($request->project_id) ? $request->project_id : ''; 
        $employee_id = isset($request->employee_id) ? $request->employee_id : ''; 

        $reports    = Task::where('employee_id',\Auth::user()->id)->get(); 
        $clients    = Client::get();
        $projects   = ProjectAssignDetails::with('project_detail')->get();
        $employees  = User::where('role',2)->where('status','Active')->get();
        
        if(role_name() == "Admin") {
            $reports = Task::query();
            if($client_id) {
                $reports->where('client_id',$client_id);
            }
            if($project_id) {
                $reports->where('project_id',$project_id);
            }
            if($employee_id) {
                $reports->where('employee_id',$employee_id);
            }
            $reports = $reports->get();
        }

        if ($request->ajax()) {
            return Datatables::of($reports)->addIndexColumn()->
            addcolumn('client_name',function($reports){
                return !is_null($reports->client_detail) ? $reports->client_detail->name : ''; 
            })->addcolumn('project_name',function($reports){
                return !is_null($reports->project_detail) ? $reports->project_detail->project_name : ''; 
            })->addcolumn('employee_name',function($reports){
                return !is_null($reports->employee_detail) ? $reports->employee_detail->name : ''; 
            })->addcolumn('man_hours',function($tasks){
                return minutes_to_hour($tasks->time_taken); 
            })->make(true);
        }
        return view('daily_report.index',compact('clients','projects','employees'));
    }
}
