<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Group;
use App\Models\User;

class AttendanceService
{


    public function attendance($id)
    {


//        dd($id);
        $today = now()->day;
        $group = Group::find($id);

        //       new code !!!

        $date = request('date', now()->format('Y-m')); // Default to current year-month if not provided

        list($year, $month) = explode('-', $date);

        // Fetch students

        $students = User::role('student')->where('group_id', $group->id)->get();

        // Fetch attendances

        $attendances = \App\Models\Attendance::where('group_id', $id)->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)->get();

        $data = [];

//        dd($attendances, $data);

        foreach ($students as $student) {

            $data[$student->name] = [];

            for ($i = 1; $i <= 31; $i++) {

                $data[$student->name][str_pad($i, 2, '0', STR_PAD_LEFT)] = ''; // Initialize all days as empty

            }

        }

        foreach ($attendances as $attendance) {

            $day = $attendance->created_at->format('d');
            $data[$attendance->user->name][$day] = $attendance->status; // Adjust status if needed

        }


//        'group'
        $students = Attendance::where('group_id', $id)->orderByDesc('created_at')->paginate(10);

        $array = ['students'=>$students,'today'=>$today,'data'=>$data,'year'=>$year,'month'=>$month ,'attendances'=>$attendances,'group'=>$group];
        return $array;

    }


}