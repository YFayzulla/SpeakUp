<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Group;
use App\Models\GroupTeacher;
use App\Models\StudentInformation;
use App\Models\User;
use Illuminate\Http\Request;

class ExtraTeacherController extends Controller
{
    public function group_delete($id)
    {
        $group = GroupTeacher::query()->find($id);
        $group->delete();
        return redirect()->back()->with('success', 'Information deleted');
    }


    public function attendanceDelete($id)
    {
        $attendance = Attendance::query()->find($id);
        $attendance->delete();
        return redirect()->back()->with('success', 'Information deleted');
    }
}
