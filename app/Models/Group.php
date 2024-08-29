<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Group extends Model
{
    use HasFactory;
    protected $fillable=['name','start_time','finish_time','room_id','monthly_payment'];

    public function teacherhasGroup(){
        return $this->hasMany(User::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function level(){
        return Level::where('id','level');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function hasTeacher(){

        return User::where('room_id',$this->room_id)->value('id');

    }
}
