<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\GroupTeacher;
use App\Models\LessonAndHistory;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TeacherAdminPanel extends Controller
{
    public function __construct(protected AttendanceService $serviceAttendance)
    {
        // Service avtomatik inject qilinadi
    }

    /**
     * O'qituvchiga biriktirilgan guruhlar ro'yxati.
     */
    public function group()
    {
        try {
            $teacherId = auth()->id();

            // OPTIMIZATSIYA: with('group') qo'shildi.
            // View faylida $item->group->name chaqirilganda ortiqcha so'rov bo'lmaydi.
            $groups = GroupTeacher::where('teacher_id', $teacherId)
                ->with('group')
                ->get();

            return view('teacher.group', compact('groups'));

        } catch (\Exception $e) {
            Log::error('TeacherAdminPanel@group error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Guruhlarni yuklashda xatolik yuz berdi.');
        }
    }

    /**
     * Davomat qilish sahifasi (Service orqali ma'lumot oladi).
     */
    public function attendance($id)
    {
        try {
            // Service ichida ham try-catch bo'lishi kerak, lekin controllerda ham ushlab turish zarar qilmaydi.
            $serviceData = $this->serviceAttendance->attendance($id);

            return view('teacher.attendance.attendance', [
                'id' => $id,
                'today' => $serviceData['today'] ?? now(),
                'data' => $serviceData['data'] ?? [],
                'year' => $serviceData['year'] ?? date('Y'),
                'month' => $serviceData['month'] ?? date('m'),
                'attendances' => $serviceData['attendances'] ?? [],
                'group' => $serviceData['group'] ?? null,
                'students' => $serviceData['students'] ?? [],
            ]);

        } catch (\Exception $e) {
            Log::error('TeacherAdminPanel@attendance error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Davomat sahifasini ochishda xatolik.');
        }
    }

    /**
     * Davomatni saqlash (Submit).
     */
    public function attendance_submit(Request $request, $id)
    {
        // 1. Validatsiya
        $request->validate([
            'lesson' => 'required|string|max:255',
            'status' => 'required|array',
        ]);

        $statuses = $request->input('status', []);

        if (empty($statuses)) {
            return redirect()->back()->with('error', 'Hech qanday talaba belgilanmadi.');
        }

        DB::beginTransaction();

        try {
            // 2. Dars mavzusini tarixga yozish
            // 'data' => 1 bu Davomat ekanligini bildiradi (AssessmentControllerda 2 edi)
            $lesson = LessonAndHistory::create([
                'name' => $request->lesson,
                'data' => 1,
                'group' => $id,
            ]);

            $attendances = [];
            $checkerId = auth()->id();
            $now = now();

            // 3. Massivni tayyorlash
            foreach ($statuses as $userId => $statusValue) {
                // Xavfsizlik uchun faqat ID raqam ekanligini tekshiramiz (agar array key string bo'lsa)
                if (!is_numeric($userId)) continue;

                $attendances[] = [
                    'user_id' => $userId,
                    'group_id' => $id,
                    'who_checked' => $checkerId,
                    // Agar formadan kelgan value (0, 1, 2) ni saqlash kerak bo'lsa: (int) $statusValue
                    // Agar shunchaki borligi uchun 1 bosilishi kerak bo'lsa: 1
                    'status' => (int)$statusValue,
                    'lesson_id' => $lesson->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // 4. Bulk Insert (Bitta so'rov bilan hammasini yozish)
            if (!empty($attendances)) {
                Attendance::insert($attendances);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Davomat muvaffaqiyatli saqlandi.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('TeacherAdminPanel@attendance_submit error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Saqlashda tizim xatoligi yuz berdi.');
        }
    }

    /**
     * Davomat bo'limi bosh sahifasi.
     */
    public function attendanceIndex()
    {
        try {
            // OPTIMIZATSIYA: with('group')
            $groups = GroupTeacher::where('teacher_id', auth()->id())
                ->with('group')
                ->get();

            return view('teacher.attendance.index', compact('groups'));
        } catch (\Exception $e) {
            Log::error('TeacherAdminPanel@attendanceIndex error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ma\'lumotlarni yuklashda xatolik.');
        }
    }

    /**
     * Display a listing of the teacher's groups.
     */
    public function groups()
    {
        $teacherId = Auth::id();
        $groups = GroupTeacher::where('teacher_id', $teacherId)->with('group')->get();
        return view('teacher.group.index', compact('groups'));
    }

    /**
     * Display a list of groups for attendance.
     */
    public function attendanceGroups()
    {
        $teacherId = Auth::id();
        $groups = GroupTeacher::where('teacher_id', $teacherId)->with('group')->get();
        return view('teacher.attendance.index', compact('groups'));
    }

    /**
     * Display a list of groups for assessment.
     */
    public function assessmentGroups()
    {
        $teacherId = Auth::id();
        $groups = GroupTeacher::where('teacher_id', $teacherId)->with('group')->get();
        return view('teacher.assessment.index', compact('groups'));
    }
}
