<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['number'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'room_id');
    }

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class, 'room_id');
    }
}
