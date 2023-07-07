<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable=['name','email','password','tel','parents_tel','payment','summa','group_id','status'];

    public function dept(){
        return $this->belongsTo(Dept::class, 'id','user_id');
    }
}
