<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $guarded = [];

    public function category(){
        return $this->belongsTo(CourseCategory::class,'cat_id','id');
    }

    public function batches(){
        return $this->hasMany(Batch::class);
    }
}
