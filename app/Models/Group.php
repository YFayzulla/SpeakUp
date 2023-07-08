<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable=['name','start_day','end_day','days','teacher_id'];

    public function teacher(){
        return $this->belongsTo(User::class,'teacher_id','id');
    }
}