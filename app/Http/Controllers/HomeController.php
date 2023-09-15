<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectAssignDetails;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $total_project_count           = ProjectAssignDetails::where('employee_id',\Auth::user()->id)->count();
        $completed_project_count       = 0;
        $wip_project_count             = 0;
        $overdue_project_count         = 0;
        $yet_to_start_project_count    = 0;
       
        if(\Auth::user()->role == 1) {
            $total_project_count           = Project::count();
            $completed_project_count       = Project::where('work_status','completed')->count();
            $wip_project_count             = Project::where('work_status','work_in_progress')->where('work_end_date','>',date('Y-m-d'))->count();
            $overdue_project_count         = Project::where('work_status','!=','completed')->where('work_end_date','<',date('Y-m-d'))->count();
            $yet_to_start_project_count    = Project::where('work_status','yet_to_start')->count();
        }

        if(request()->ajax() && isset($request->from)) {
            if($request->from == "completed") {
                $project_details       = Project::where('work_status',$request->from)->get();
            } elseif($request->from == "work_in_progress") {
                $project_details        = Project::where('work_status',$request->from)->where('work_end_date','>',date('Y-m-d'))->get();
            } elseif($request->from == "overdue") {
                $project_details        = Project::where('work_status','!=','completed')->where('work_end_date','<',date('Y-m-d'))->get();
            } elseif($request->from == "yet_to_start") {
                $project_details        = Project::where('work_status',$request->from)->get();
            } else {
                $project_details = Project::get();
                if(\Auth::user()->role == 2) {
                     $project_details   = ProjectAssignDetails::where('employee_id',\Auth::user()->id)->get();
                }
            }
            $data['header_title']      = ($request->from == "work_in_progress") ? "Progress Projects" : ucfirst($request->from)." Projects";
            if($project_details->count() > 0) {
                    $html =  '<table class="table table-bordered project-datatable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th width="250px">Client Name</th>
                            <th width="300px">Project Name</th>
                            <th>Order Value</th>
                            <th width="200px">Work Start Date</th>
                            <th width="200px">Work End Date</th>';
                    if(\Auth::user()->role == 1) {
                        $html .= '<th>Action</th>';
                    }
                    $html .= '</tr></thead><tbody>'; 
                    foreach ($project_details as $key => $value) {  
                        if(\Auth::user()->role == 2) {
                            $html .= "<tr>
                                <td>". $key+1 ."</td>
                                <td>".$value->client_detail->name . "</td>
                                <td>".$value->project_detail->project_name . "</td>
                                <td>".$value->project_detail->order_value . "</td>
                                <td>".date('d-m-Y',strtotime($value->project_detail->work_start_date)) . "</td>
                                <td>".date('d-m-Y',strtotime($value->project_detail->work_end_date)) . "</td>
                             </tr>";
                        } else {
                            $html .= "<tr>
                                <td>". $key+1 ."</td>
                                <td>".$value->client_name->name . "</td>
                                <td>".$value->project_name . "</td>
                                <td>".$value->order_value . "</td>
                                <td>".date('d-m-Y',strtotime($value->work_start_date)) . "</td>
                                <td>".date('d-m-Y',strtotime($value->work_end_date)) . "</td>
                                <td><a href='".url("/project_edit/".$value->id)."' target='_blank'><button class='btn btn-primary' style='width:75px;'><i class='fa fa-eye' aria-hidden='true'></i>  View</button></a></td>
                             </tr>";
                        }
                    }
                    $html .= '</tbody></table>';
            } else {
                $html = '<div class="d-flex">
                    <img src="https://t4.ftcdn.net/jpg/04/72/65/73/360_F_472657366_6kV9ztFQ3OkIuBCkjjL8qPmqnuagktXU.jpg" width="200px" class="m-auto">
                </div>';
            }
            $data['html_body']  = $html;
            return response()->json($data);
        }

        return view('dashboard',compact('total_project_count','completed_project_count','wip_project_count','overdue_project_count','yet_to_start_project_count'));
    }
}
