<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\ProjectAssignDetails;

class Project extends Model
{
    use HasFactory;
    protected $table = "projects";
    protected $fillable = ['query_no','client_id','project_id','project_description','work_order_no','work_start_date','work_end_date','work_status','work_status_remarks','order_value','collected_till','is_billable','delete_remarks','delete_status','actual_delivery_date','client_po_doc_name','client_po_description','proposal_doc_name','proposal_description','work_order_doc_name','work_order_description'];

    public function client_name()
    {
        return $this->belongsTo(Client::class,'client_id','id');
    }

    public function project_assign_details()
    {
        return $this->hasMany(ProjectAssignDetails::class,'project_id','id');
    }
    
    public function getEstimationHoursAttribute()
    {
        return $this->project_assign_details()->get()->sum('hours');
    }
    
    public function task()
    {
        return $this->hasMany(Task::class,'project_id','id');
    }
    
    public function getActualWorkingHoursAttribute()
    {
        return $this->task()->get()->sum('time_taken');
    }
    
    public function getActualDeliveryDateFormatAttribute() 
    {
        return !is_null($this->actual_delivery_date) ? date('d-m-Y',strtotime($this->actual_delivery_date)) : '';
    }
    
    public function otherdocuments()
    {
        return $this->hasMany(ProjectOtherDocuments::class,'project_id','id');
    }
}
