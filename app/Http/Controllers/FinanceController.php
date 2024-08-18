<?php

namespace App\Http\Controllers;

use App\Models\GroupTeacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{


    public function index()
    {
// Fetch all teachers
        $teachers = User::query()->role('user')->get();

//        foreach ($teachers as $teacher) {
//            // Get all group IDs associated with the teacher
//            $groupIds = GroupTeacher::query()
//                ->where('teacher_id', $teacher->id)
//                ->pluck('group_id');
//
//            // Get the students in these groups
//            $students = User::query()
//                ->role('student')
//                ->whereIn('group_id', $groupIds)
//                ->get(['name']); // Fetch student names (or other details you need)
//
//            $teacher->name ;
//            foreach ($students as $student) {
//                echo "- Student: {$student->name}\n";
//            }
//            echo "<br>";
//        }
//
//
//        echo $teachers[1] -> teacherHasStudents(2);
//        echo $teachers[0] -> teacherPayment();

//        dd($teachers);

        return view('user.finance.index');


    }
}