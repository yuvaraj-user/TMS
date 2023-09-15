<?php
use App\Models\Sidemenu;
use App\Models\Role;
use App\Models\ProjectAssignDetails;
use App\Models\Task;
use App\Models\User;

function role_permission($role_id) {
    $role        = Role::find($role_id);
    $permissions = explode(',',$role->menu_permissions);
    return $permissions;
}
function side_menu(){
    $permission = role_permission(\Auth::user()->role); 
    $main_menu = Sidemenu::whereIn('id',$permission)->where('parent_id',0)->Orderby('order_level','ASC')->get();
    return $main_menu;
}

function sub_menu(){
    $permission = role_permission(\Auth::user()->role); 
    $sub_menu = Sidemenu::whereIn('id',$permission)->where('parent_id','!=',0)->Orderby('order_level','ASC')->get();
    return $sub_menu;
}

function sub_menu_check($id)
{
    $check = Sidemenu::where('parent_id',$id)->count();
    return ($check > 0) ? true : false;
}

function minutes_to_hour($minutes)
{
    $total_minutes      = $minutes;
    $time_taken_hours   = floor($total_minutes / 60);
    $time_taken_minutes = $total_minutes - ($time_taken_hours * 60);
    return time_format_change($time_taken_hours)." : ".time_format_change($time_taken_minutes)."";
}

function time_format_change($value)
{
    if (strlen($value) > 0 && strlen($value) == 1) {
        $value = '0' . $value;
    } elseif ($value == 0) {
        $value = '0' . $value;
    }
    return $value;
}

function role_name()
{
    $role = Role::find(\Auth::user()->role);
    return !empty($role) ? $role->role_name : '';
}

function man_estimation_hours($project_id,$employee_id)
{
    $estimation_hours = ProjectAssignDetails::where('project_id',$project_id)->where('employee_id',$employee_id)->first();
    return !empty($estimation_hours) ? minutes_to_hour($estimation_hours->hours * 60) : minutes_to_hour(0);
}

function completed_estimation_minutes($project_id,$employee_id)
{
    $completed_estimation_hours = task::where('project_id',$project_id)->where('employee_id',$employee_id)->sum('time_taken');
    return !empty($completed_estimation_hours) ? $completed_estimation_hours : '';
}

function employee_name($id)
{
    $name = User::find($id)->name;
    return !empty($name) ? $name : ''; 
}

function date_delay($from,$to)
{
    $start = new DateTime($from);
    $end   = new DateTime($to);
    // $pos_diff = $start->diff($end)->format("%r%a");
    $neg_diff = $end->diff($start)->format("%r%a");
    return $neg_diff;
}