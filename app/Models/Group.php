<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Throwable;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_time',
        'finish_time',
        'room_id',
        'monthly_payment'
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(User::class, 'group_id');
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_teachers', 'group_id', 'teacher_id');
    }

    public function getStudentsCountAttribute(): int
    {
        return $this->students()->count();
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($group) {
            if (!is_null($group->room_id)) {
                $room = Room::find($group->room_id);
                if ($room && !is_null($room->teacher_id)) {
                    DB::transaction(function () use ($group, $room) {
                        GroupTeacher::create([
                            'group_id' => $group->id,
                            'teacher_id' => $room->teacher_id,
                        ]);
                    });
                }
            }
        });

        static::updated(function ($group) {
            if ($group->isDirty('room_id') && !is_null($group->room_id)) {
                $room = Room::find($group->room_id);
                if ($room && !is_null($room->teacher_id)) {
                    DB::transaction(function () use ($group, $room) {
                        GroupTeacher::updateOrCreate(
                            ['group_id' => $group->id],
                            ['teacher_id' => $room->teacher_id]
                        );
                    });
                }
            }
        });
    }
}
