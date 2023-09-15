<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;
use App\Models\Client;

class ProjectAssignDetails extends Model
{
    use HasFactory;
    protected $table = "project_assign_details";
    protected $fillable = [
        'project_id',
        'client_id',
        'employee_id',
        'hours',
        'description'
    ];

    public function project_detail()
    {
        return $this->belongsTo(Project::class,'project_id','id');
    }

    public function client_detail()
    {
        return $this->belongsTo(Client::class,'client_id','id');
    }
    
    public function employee_detail()
    {
        return $this->belongsTo(User::class,'employee_id','id');
    }
    
}
