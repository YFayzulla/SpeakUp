<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable=['user_id','group_id','date','status'];
    public function student(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function group(){
        return $this->belongsTo(Group::class,'group_id','id');
    }
}

