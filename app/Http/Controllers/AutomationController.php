<?php

namespace App\Http\Controllers;

use App\Mail\DailyReportMail;
use App\Mail\DueReportMail;
use App\Mail\MonthlyProjectReportMail;
use App\Mail\ProgressProjectReportMail;
use App\Mail\DevelopersIndividualReportMail;
use App\Mail\ProjectDeliveryReportMail;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AutomationController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
    }
    public function daily_report(Request $request)
    {
        $to_mail = [
            'hr@mazenetsolution.com',
            'productivity@mazenetsolution.com',
            'dharmaraj@mazenettech.com',
            'manoj_p@mazenetsolution.com'
        ];
        $today        = date('Y-m-d');
        $current_time = date('h:i A');
        if ($current_time == "07:45 PM") {
        $users = User::where('id', '!=', 1)->where('role', '!=', 1)->where('email', '!=', 'jr.sysadmin@mazenetsolution.com')->get('id')->toArray();

        $daily_reports   = Task::with(['project_detail', 'client_detail'])->where('date', $today)->get()->groupBy('employee_id')->toArray();

        foreach ($users as $key => $value) {
            $exist = false;
            foreach ($daily_reports as $k => $val) {
                if ($value['id'] == $k) {
                    $exist = true;
                    break;
                }
            }
            if ($exist == false) {
                $daily_reports[$value['id']][0] = [];
            }
        }
        if (count($daily_reports) > 0) {
            Mail::to($to_mail)->send(new DailyReportMail($daily_reports));
        }
        // return view('automation.daily_report', compact('daily_reports'));
        }
    }

    public function closed_project_report()
    {
        $to_mail = [
            'productivity@mazenetsolution.com',
            'dharmaraj@mazenettech.com',
            'ashokraj@mazenetsolution.com',
        ];


        $cc_mail = ['manoj_p@mazenetsolution.com', 'jr_developer@mazenetsolution.com', 'accounts@mazenetsolution.com'];
        $due_date   = date('Y-m-d', strtotime('-1 day'));
        $today_date = date('Y-m-d');
        $current_time = date('h:i A');
        if ($current_time == "11:00 AM") {
            $due_report = Project::with(['project_assign_details' => function ($query) {
                $query->with('employee_detail');
            }, 'client_name'])->where('work_end_date', $due_date)->get();
            foreach ($due_report as $key => $value) {
                foreach ($value->project_assign_details as $k => $val) {
                    $val->utilized_hours  = Task::where('project_id', $val->project_id)->where('employee_id', $val->employee_id)->sum('time_taken');
                    $val->remaining_hours = ($val->hours * 60) - (int) $val->utilized_hours;
                }
            }

            $due_report = $due_report->append(['estimation_hours', 'actual_working_hours'])->toArray();
            if (count($due_report) > 0) {
                Mail::to($to_mail)->cc($cc_mail)->send(new DueReportMail($due_report));
            }

            // return view('automation.due_date_report',compact('due_report'));
        }
    }

    public function monthly_project_report()
    {
        $to_mail = [
            'productivity@mazenetsolution.com',
            'hr@mazenetsolution.com',
            'dharmaraj@mazenettech.com',
            'mani@mazenetsolution.com',
            'ashokraj@mazenetsolution.com',
            'accounts@mazenetsolution.com'
        ];



        $cc_mail = ['manoj_p@mazenetsolution.com', 'jr_developer@mazenetsolution.com'];
        $current_time = date('h:i A');
        if (date('Y-m-d', strtotime('first day of this month')) == date('Y-m-d') && $current_time == "01:00 AM") {
            $start_date = date('Y-m-d', strtotime('first day of previous month'));
            $end_date   = date('Y-m-d', strtotime('last day of previous month'));
            $billable = Project::where('is_billable', 'yes')->with(['task' => function ($query) use ($start_date, $end_date) {
                $query->where('date', ">=", $start_date)->where('date', "<=", $end_date);
            }])->get();

            $non_billable = Project::where('is_billable', 'no')->with(['task' => function ($query) use ($start_date, $end_date) {
                $query->where('date', ">=", $start_date)->where('date', "<=", $end_date);
            }])->get();

            $projects   = Project::with(['client_name', 'task' => function ($query) use ($start_date, $end_date) {
                $query->where('date', ">=", $start_date)->where('date', "<=", $end_date);
            }])->get();
            $employees = User::withSum(['task' => function ($query) use ($start_date, $end_date) {
                $query->where('date', ">=", $start_date)->where('date', "<=", $end_date);
            }], 'time_taken')->where('id', '!=', 1)->where('role', '!=', 1)->where('email', '!=', 'jr.sysadmin@mazenetsolution.com')->get();
            $project_total_hours  = Task::where('date', ">=", $start_date)->where('date', "<=", $end_date)->sum('time_taken');
            Mail::to($to_mail)->cc($cc_mail)->send(new MonthlyProjectReportMail($projects, $employees, $project_total_hours, $billable, $non_billable, $start_date, $end_date));
            // return view('automation.monthly_project_report', compact('projects', 'employees','project_total_hours','billable','non_billable','start_date','end_date'));
        }
    }

    public function progress_project_weekly_report()
    {
        $to_mail = [
            'productivity@mazenetsolution.com',
            'dharmaraj@mazenettech.com',
            'ashokraj@mazenetsolution.com',
            'accounts@mazenetsolution.com'
        ];

        // $to_mail = [
        //     'php@mazenetsolution.com'
        // ];

        $cc_mail = ['manoj_p@mazenetsolution.com', 'jr_developer@mazenetsolution.com'];
        if (date('l') == "Monday" && date('h:i A') == "09:00 AM") {
            $progress_projects = Project::with(['project_assign_details', 'task', 'client_name'])->where('work_status', 'work_in_progress')->where('delete_status', '!=', 1)->get();
            Mail::to($to_mail)->cc($cc_mail)->send(new ProgressProjectReportMail($progress_projects));
            // return view('automation.progress_project_weekly_report',compact('progress_projects'));
        }
    }

    public function developers_individual_weekly_report()
    {
        $cc_mail = [
            'productivity@mazenetsolution.com',
            'dharmaraj@mazenettech.com',
            'ashokraj@mazenetsolution.com',
            'manoj_p@mazenetsolution.com',
            'hr@mazenetsolution.com'
        ];
        if (date('l') == "Friday" && date('h:i A') == "09:00 AM") {
            $last_friday_date  = date('Y-m-d', strtotime('last friday'));
            $yesterday_date    = date('Y-m-d', strtotime('yesterday'));
            $employees = User::with(['assigned_projects', 'overall_project_working_hours', 'task' => function ($query) use ($last_friday_date, $yesterday_date) {
                $query->where('date', '>=', $last_friday_date)->where('date', '<=', $yesterday_date);
            }])->where('role', '!=', 1)->where('email', '!=', 'jr.sysadmin@mazenetsolution.com')->get();

            foreach ($employees as $key => $value) {
                $employee_report_details = $value;
                Mail::to($employee_report_details->email)->cc($cc_mail)->send(new DevelopersIndividualReportMail($employee_report_details, $last_friday_date, $yesterday_date));
                // return view('automation.developers_individual_weekly_report', compact('employee_report_details','last_friday_date','yesterday_date'));
            }
        }
    }

    public function project_delivery_report()
    {
        $to_mail = [
            'productivity@mazenetsolution.com',
            'dharmaraj@mazenettech.com',
            'ashokraj@mazenetsolution.com',
            'accounts@mazenetsolution.com',
            'mani@mazenetsolution.com'
        ];


        $cc_mail = ['manoj_p@mazenetsolution.com', 'jr_developer@mazenetsolution.com'];
        $current_time = date('h:i A');
        if (date('Y-m-d', strtotime('first day of this month')) == date('Y-m-d') && $current_time == "09:00 AM") {
            $start_date = date('Y-m-d', strtotime('first day of previous month'));
            $end_date   = date('Y-m-d', strtotime('last day of previous month'));

            $delivered_projects = Project::where('work_status', 'completed')->where('actual_delivery_date', '>=', $start_date)->where('actual_delivery_date', '<=', $end_date)->get();

            Mail::to($to_mail)->cc($cc_mail)->send(new ProjectDeliveryReportMail($delivered_projects, $start_date));

            //return view('automation.project_delivery_report', compact('delivered_projects', 'start_date'));
        }
    }
}
