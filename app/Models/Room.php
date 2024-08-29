<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['room'];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'room_id');
    }

    public function teacher($id)
    {
        return User::where('room_id', $id)->first();

    }

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class, 'room_id');
    }


    public function roomTeacher($id){
        if (User::where('room_id', $id)->exists()){
            return false;
        }
        return true;
    }

}
