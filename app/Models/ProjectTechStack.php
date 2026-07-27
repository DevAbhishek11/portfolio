<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectTechStack extends Model
{
    protected $fillable = ['project_id', 'name', 'category', 'icon'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
