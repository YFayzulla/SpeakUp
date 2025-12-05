<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\GroupTeacher;
use App\Models\LessonAndHistory;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\AttendanceService;
use Illuminate\Support\Facades\DB;

class TeacherAdminPanel extends Controller
{
    public function __construct(protected AttendanceService $serviceAttendance)
    {
    }

    public function group()
    {
        $teacherId = auth()->id();
        $groups = GroupTeacher::where('teacher_id', $teacherId)->get();
        return view('teacher.group', compact('groups'));
    }

    public function attendance($id)
    {
        $serviceData = $this->serviceAttendance->attendance($id);

        // Assuming $serviceData['students'] provides all necessary student data.
        // The extra query for 'users' is removed for optimization.
        // If 'users' was for a different purpose, it should be restored with a clear name.
        return view('teacher.attendance.attendance', [
            'id' => $id,
            'today' => $serviceData['today'],
            'data' => $serviceData['data'],
            'year' => $serviceData['year'],
            'month' => $serviceData['month'],
            'attendances' => $serviceData['attendances'],
            'group' => $serviceData['group'],
            'students' => $serviceData['students'],
        ]);
    }

    public function attendance_submit(Request $request, $id)
    {
        $statuses = $request->input('status', []);

        if (empty($statuses)) {
            return redirect()->back()->with('error', 'Hech qanday talaba belgilanmadi.');
        }

        DB::transaction(function () use ($request, $id, $statuses) {
            $lesson = LessonAndHistory::create([
                'name' => $request->lesson,
                'data' => 1, // Assuming 1 means attendance check
                'group' => $id,
            ]);

            $attendances = [];
            $checkerId = auth()->id();
            $now = now();

            foreach ($statuses as $userId => $status) {
                $attendances[] = [
                    'user_id' => $userId,
                    'group_id' => $id,
                    'who_checked' => $checkerId,
                    'status' => 1, // Assuming 'status' in request is a checkbox, so always present (1)
                    'lesson_id' => $lesson->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            Attendance::insert($attendances);
        });

        return redirect()->back()->with('success', 'Davomat muvaffaqiyatli saqlandi.');
    }

    public function attendanceIndex()
    {
        $groups = GroupTeacher::where('teacher_id', auth()->id())->get();
        return view('teacher.attendance.index', compact('groups'));
    }
}
