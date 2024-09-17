<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\GroupTeacher;
use App\Models\LessonAndHistory;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\AttendanceService;

class TeacherAdminPanel extends Controller
{

    public function __construct(
        protected AttendanceService $serviceAttendance,
    )
    {

    }

    public function group()
    {
        $id = auth()->id();
        $groups = GroupTeacher::where('teacher_id', $id)->get();
        return view('teacher.group', compact('groups'));
    }

//one
    public function attendance($id)
    {

        $service = $this->serviceAttendance->attendance($id);

//        dd($service);

        return view('teacher.attendance.attendance', [
//            for checking
            'users' => User:: where('group_id', $id)->orderBy('name')->get(),
            'id' => $id,
//            for list
            'today' => $service['today'],
            'data' => $service['data'],
            'year' => $service['year'],
            'month' => $service['month'],
            'attendances' => $service['attendances'],
            'group' => $service['group'],
            'students'=>$service['students'],
        ]);

    }

    public function attendance_submit(Request $request, $id)
    {
        $lesson = LessonAndHistory::create([
            'name' => $request->lesson,
            'data' => 1,
            'group' => $id,
        ]);

//        dd($request->status);
        if ($request->status) {
            foreach ($request->status as $name => $status) {
                $user_id = auth()->id();
                Attendance::create([
                    'user_id' => $name,
                    'group_id' => $id,
                    'who_checked' => $user_id,
                    'status' => 1,
                    'lesson_id' => $lesson->id,
                ]);
            }
            return redirect()->back()->with('success', 'Saved');

        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }

    }

//    second
    public function attendanceIndex()
    {
//        dd('s');
        return view('teacher.attendance.index', [
            'groups' => GroupTeacher::query()->where('teacher_id', auth()->id())->get(),
        ]);
    }


}

