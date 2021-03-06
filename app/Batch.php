<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $guarded = [];

    public function course(){
        return $this->hasOne(Course::class,'id','course_id');
    }

    public function students(){
        return $this->hasManyThrough(Student::class,StidentHasBatch::class,'batch_id','id','id','student_id');
    }
}
