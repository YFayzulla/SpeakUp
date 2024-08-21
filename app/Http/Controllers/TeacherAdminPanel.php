<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Group;
use App\Models\GroupTeacher;
use App\Models\LessonAndHistory;
use App\Models\StudentInformation;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherAdminPanel extends Controller
{

    public function group()
    {
        $id = auth()->id();
        $groups = GroupTeacher::where('teacher_id', $id)->get();
        return view('teacher.group', compact('groups'));
    }
//one
    public function attendance($id)
    {

        $students = User:: where('group_id', $id)->orderBy('name')->get();
        return view('teacher.attendance.attendance', compact('students', 'id'));

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
                    'lesson_id' => $lesson->id,
                ]);
            }
            return redirect()->route('attendance')->with('success', 'Saved');

        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }

    }





//    second
    public function attendanceIndex()
    {

//        dd('s');
        return view('teacher.attendance.index',[
            'groups'=>Group::where('id','!=',1)->orderBy('name')->get(),
        ]);
    }

    public function attendanceList($id)
    {
        $today = now()->day;
        $group = Group::find($id);

        //       new code !!!

        $date = request('date', now()->format('Y-m')); // Default to current year-month if not provided

        list($year, $month) = explode('-', $date);

        // Fetch students

        $students = User::role('student')->where('group_id', $group->id)->get();

        // Fetch attendances

        $attendances = Attendance::where('group_id', $id)->whereYear('created_at', $year)
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

//new code ended

        return view('teacher.attendance.attendance_list', [
            'group' => $group,
            //new items
            'students' => Attendance::where('group_id', $id)->orderByDesc('created_at')->paginate(10),
            'today' => $today,
            'data' => $data,
            'year' => $year,
            'month' => $month,
            'attendances' => $attendances,

        ]);

       
    }


}
