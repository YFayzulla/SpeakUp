<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{

    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'password',
        'passport',
        'date_born',
        'location',
        'parents_name',
        'parents_tel',
        'group_id',
        'photo',
        'should_pay',
        'description',
        'status',
        'percent',
        'mark',
        'room_id',
    ];

    public function teacherHasStudents()
    {
        $groupIds = GroupTeacher::where('teacher_id', $this->id)->pluck('group_id');
        return User::role('student')->whereIn('group_id', $groupIds)->count();
    }

    public function teacherPayment()
    {
        // Eager load groups with their student count to optimize queries
        $groups = GroupTeacher::where('teacher_id', $this->id)
            ->with(['group' => function ($query) {
                $query->withCount('students');
            }])
            ->get();

        $totalPayment = $groups->sum(function ($groupTeacher) {
            if ($groupTeacher->group) {
                return $groupTeacher->group->monthly_payment * $groupTeacher->group->students_count;
            }
            return 0;
        });

        return $totalPayment * $this->percent / 100;
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function studentinformation()
    {
        return $this->hasMany(StudentInformation::class);
    }

    public function studenthistory()
    {
        return $this->hasMany(HistoryPayments::class);
    }

    public function assessment()
    {
        return $this->hasMany(Assessment::class);
    }

    /**
     * Get the debt record associated with the user.
     */
    public function deptStudent()
    {
        return $this->hasOne(DeptStudent::class, 'user_id', 'id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function teacherHasGroup()
    {
        return GroupTeacher::where('teacher_id', $this->id)->count();
    }

    public function checkAttendanceStatus()
    {
        return $this->attendances()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->exists();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    private function teacherGroups()
    {
        return $this->belongsTo(GroupTeacher::class);
    }

    public function room()
    {
        return $this->hasOne(Room::class, 'id', 'room_id');
    }

    public function studentsGroup()
    {
        $group = Group::find($this->group_id);

        if (!$group) {
            return 'non of group';
        }

        $room = Room::where('id', $group->room_id)->first();

        return $room ? $room->room .'->' .$group->name : 'students without a group';
    }
}
