<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\GroupTeacher;

class ExtraTeacherController extends Controller
{
    /**
     * Delete a specific group-teacher assignment.
     *
     * @param int $id The ID of the GroupTeacher record.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function group_delete(int $id)
    {
        $groupTeacher = GroupTeacher::findOrFail($id);
        $groupTeacher->delete();
        return redirect()->back()->with('success', 'Guruh birikmasi muvaffaqiyatli o\'chirildi.');
    }

    /**
     * Delete a specific attendance record.
     *
     * @param int $id The ID of the Attendance record.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function attendanceDelete(int $id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();
        return redirect()->back()->with('success', 'Davomat yozuvi muvaffaqiyatli o\'chirildi.');
    }
}
