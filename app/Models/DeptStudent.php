<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeptStudent extends Model
{
    use HasFactory;
    protected $fillable=['user_id','payed','should_pay','dept','status_month','date'];

    public $timestamps = false;

    public function student(){
        return $this->belongsTo(User::class,'user_id','id');
    }

}
