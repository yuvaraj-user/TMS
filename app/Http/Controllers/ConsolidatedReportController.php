<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class ConsolidatedReportController extends Controller
{
    public $method;
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->method = $request->method();
    }
    public function index(Request $request)
    {

        $client_id    = isset($request->client_id) ? $request->client_id : '';
        $employee_id  = isset($request->employee_id) ? $request->employee_id : '';
        $project_id   = isset($request->project_id) ? $request->project_id : '';
        $employee_id  = isset($request->employee_id) ? $request->employee_id : '';
        $work_status  = (isset($request->status) && $request->status != 'all') ? $request->status : '';
        $start_date   = isset($request->start_date) ? date('Y-m-d', strtotime($request->start_date)) : '';
        $end_date     = isset($request->end_date) ? date('Y-m-d', strtotime($request->end_date)) : '';

        $clients      = Client::get();
        $employees    = User::where('status', 'Active')->get();
        $all_projects = Project::where('delete_status', '!=', 1)->get();

 
        $projects     = Project::where('delete_status', '!=', 1)->with(['client_name','project_assign_details','task'])->whereHas('client_name', function ($query) use ($client_id) {
            if ($client_id) {
                $query->where('id', $client_id);
            } 
        })->whereHas('project_assign_details',function($qry) use($employee_id){
            if ($employee_id) {
                $qry->where('employee_id', $employee_id);
            } 
        })->when(($work_status == '' && $project_id == '' && $start_date == '' && $end_date == ''),function($wry) use($employee_id,$client_id){
            $wry->orWhereHas('task',function($qrry) use($employee_id,$client_id){
                if ($employee_id) {
                    $qrry->where('employee_id', $employee_id);
                } 
                if($client_id) {
                     $qrry->where('client_id', $client_id);
                }
            });
        })->withSum(['project_assign_details as planned_man_hours' => function($rrr) use($employee_id) {
            if ($employee_id) {
                $rrr->where('employee_id',$employee_id);
            }
        }], 'hours')->withSum(['Task as completed_man_minutes' => function($yry) use($employee_id){
            if ($employee_id) {
                $yry->where('employee_id', $employee_id);
            }
        }], 'time_taken');


        if ($project_id) {
            $projects->where('id', $project_id);
        }
        if ($work_status) {
            $projects->where('work_status', $work_status);
        }
        if($start_date) {
            $projects->where('work_start_date','>=',$start_date);
        }
        if($end_date) {
            $projects->where('work_end_date','<=',$end_date);
        }

        $projects     = $projects->get();

        $work_status  = ($request->status == "all") ? "all" : $work_status;

        return view('consolidated_report.index', compact('clients', 'projects', 'employees', 'all_projects','client_id', 'project_id','work_status','start_date','end_date','employee_id'));
    }
}
