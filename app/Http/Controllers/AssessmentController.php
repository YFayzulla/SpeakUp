<?php

namespace App\Http\Controllers;

use App\Models\ActiveStudent;
use App\Models\Assessment;
use App\Models\Attendance;
use App\Models\Group;
use App\Models\GroupTeacher;
use App\Models\LessonAndHistory;
use App\Models\StudentInformation;
use App\Models\User;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $id = auth()->id();
        $groups = GroupTeacher::where('teacher_id', $id)->get();

        return view('teacher.assessment.index', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.Reason
     *
     * @param \App\Models\Assessment $assessment
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $students = User::where('group_id', $id)->orderBy('name')->get();
        $groups = Group::OrderBy('name')->get();
        return view('teacher.assessment.make_markes', compact('students', 'id', 'groups'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Assessment $assessment
     * @return \Illuminate\Http\Response
     */
    public function edit(Assessment $assessment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Assessment $assessment
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $end_marks = $request->end_mark;
        $rec_groups = $request->recommended;
        $reasons = $request->reason;
        $users = $request->student;

        $count = count($reasons);
        $group = Group::findOrFail($id);

        $history = LessonAndHistory::create([
            'group' => $group->id,
            'name' => $request->lesson ?? auth()->user()->name,
            'data' => 2
        ]);

        $assessments = [];
        $studentsToUpdate = [];

        for ($i = 0; $i < $count; $i++) {
            $mark = $end_marks[$i] ?? null;
            if ($mark !== null && $mark != 0) {
                $assessments[] = [
                    'get_mark' => $mark,
                    'user_id' => $users[$i],
                    'for_what' => $reasons[$i],
                    'rec_group' => $rec_groups[$i],
                    'group' => $group->name,
                    'history_id' => $history->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            $studentsToUpdate[$users[$i]] = $mark;
        }
// Bulk insert for better performance
//        if (!empty($assessments)) {
//            Assessment::insert($assessments);
//        }
//// Bulk update marks
//        User::whereIn('id', array_keys($studentsToUpdate))->get()->each(function ($student) use ($studentsToUpdate) {
//            $student->update(['mark' => $studentsToUpdate[$student->id]]);
//            // Handle active students
//            if (!$student->checkAttendanceStatus()) {
//                ActiveStudent::create(['user_id' => $student->id]);
//            }
//        });

        return redirect()->route('assessment.index')->with('success', 'Grades saved');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Assessment $assessment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Assessment $assessment)
    {
        //
    }
}
