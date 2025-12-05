<?php

namespace App\Http\Controllers;

use App\Models\ActiveStudent;
use App\Models\Assessment;
use App\Models\Group;
use App\Models\GroupTeacher;
use App\Models\LessonAndHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teacherId = auth()->id();
        $groups = GroupTeacher::where('teacher_id', $teacherId)->get();

        return view('teacher.assessment.index', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // This action is not implemented.
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // This action is not implemented.
    }

    /**
     * Display the specified resource.
     *
     * @param int $id The Group ID.
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $group = Group::findOrFail($id);
        $students = User::where('group_id', $id)->orderBy('name')->get();
        $allGroups = Group::orderBy('name')->get();

        return view('teacher.assessment.make_markes', [
            'students' => $students,
            'id' => $id,
            'groups' => $allGroups, // 'groups' nomini view fayli bilan mosligi uchun saqlab qoldim
            'groupName' => $group->name
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Assessment $assessment
     * @return \Illuminate\Http\Response
     */
    public function edit(Assessment $assessment)
    {
        // This action is not implemented.
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id The Group ID.
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $end_marks = $request->input('end_mark', []);
        $rec_groups = $request->input('recommended', []);
        $reasons = $request->input('reason', []);
        $users = $request->input('student', []);

        if (empty($reasons)) {
            return redirect()->route('assessment.index')->with('error', 'Saqlash uchun ma\'lumot topilmadi.');
        }

        $group = Group::findOrFail($id);

        DB::transaction(function () use ($request, $group, $end_marks, $rec_groups, $reasons, $users) {
            $history = LessonAndHistory::create([
                'group' => $group->id,
                'name' => $request->lesson ?? auth()->user()->name,
                'data' => 2
            ]);

            $assessments = [];
            $studentsToUpdate = [];

            foreach ($reasons as $i => $reason) {
                $mark = $end_marks[$i] ?? null;
                $userId = $users[$i] ?? null;

                if ($userId && $mark !== null && $mark != 0) {
                    $assessments[] = [
                        'get_mark' => $mark,
                        'user_id' => $userId,
                        'for_what' => $reason,
                        'rec_group' => $rec_groups[$i] ?? null,
                        'group' => $group->name,
                        'history_id' => $history->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                if ($userId) {
                    $studentsToUpdate[$userId] = ['mark' => $mark];
                }
            }

            if (!empty($assessments)) {
                Assessment::insert($assessments);
            }

            $studentIds = array_keys($studentsToUpdate);
            if (!empty($studentIds)) {
                $students = User::whereIn('id', $studentIds)->get();

                foreach ($students as $student) {
                    if (isset($studentsToUpdate[$student->id])) {
                        $student->update(['mark' => $studentsToUpdate[$student->id]['mark']]);

                        // Eslatma: checkAttendanceStatus() har bir talaba uchun alohida so'rov yuborishi mumkin.
                        // Agar bu sekin ishlasa, optimallashtirish talab etiladi.
                        if (!$student->checkAttendanceStatus()) {
                            ActiveStudent::firstOrCreate(['user_id' => $student->id]);
                        }
                    }
                }
            }
        });

        return redirect()->route('assessment.index')->with('success', 'Baholar muvaffaqiyatli saqlandi.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Assessment $assessment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Assessment $assessment)
    {
        // This action is not implemented.
    }
}
