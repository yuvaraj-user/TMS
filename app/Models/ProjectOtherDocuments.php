<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectOtherDocuments extends Model
{
    use HasFactory;
    protected $table = "project_other_documents";
    protected $fillable = ['project_id','other_docs_name','other_docs_description'];

}
