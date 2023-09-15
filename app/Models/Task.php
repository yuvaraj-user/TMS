<?php

namespace App\Models;

use App\Events\taskupdate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\Project;
use App\Models\User;

class Task extends Model
{
    use HasFactory;
    protected $table   = "task";
    protected $filable = ['date','client_id','project_id','employee_id','task_name','task_description','time_taken','status','status_remarks','delete_remarks','delete_status','updated_by'];

    protected $dispatchesEvents = [
        'updated' => taskupdate::class,
        // 'deleted' => UserDeleted::class,
    ];

    public function getManHoursAttribute()
    {
        return $this->time_taken;
    }

    public function client_detail()
    {
        return $this->belongsTo(Client::class,'client_id','id');
    }

    public function project_detail()
    {
        return $this->belongsTo(Project::class,'project_id','id');
    }

    public function employee_detail()
    {
        return $this->belongsTo(User::class,'employee_id','id');
    }

}
