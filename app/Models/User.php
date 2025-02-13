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

//    public function teacherhasGroup()
//    {
//        return $this->hasMany(Group::class);
//    }

    public function teacherHasStudents()
    {

        $groupIds = GroupTeacher::query()
            ->where('teacher_id', $this->id)
            ->pluck('group_id');

        return User::role('student')
            ->whereIn('group_id', $groupIds)
            ->count();

    }

    public function teacherPayment()
    {

        $summa = 0;
        $groups = GroupTeacher::query()->where('teacher_id', $this->id)->get();

        foreach ($groups as $group) {
            $payment = Group::query()->findOrFail($group->group_id);
            $number = User::query()->where('group_id', $group->group_id)->count();
//            dd($number,$payment->monthly_payment);
            $summa += $payment->monthly_payment * $number;
        }

        $summa = $summa * $this->percent / 100;
        return $summa;

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

    public function studentdept()
    {
        return $this->hasOne(DeptStudent::class, 'user_id', 'id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function teacherHasGroup()
    {
        return GroupTeacher::query()->where('teacher_id', $this->id)->count();
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
