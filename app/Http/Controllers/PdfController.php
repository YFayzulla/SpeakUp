<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Attendance;
use App\Models\Group;
use App\Models\HistoryPayments;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PDF;

class PdfController extends Controller
{
    public function __construct()
    {
        // Set a higher execution time limit for all methods in this controller
        set_time_limit(300);
    }

    public function RoomListPDF(Request $request)
    {
        $startDate = $request->input('startDate') ? Carbon::parse($request->input('startDate')) : null;
        $endDate = $request->input('endDate') ? Carbon::parse($request->input('endDate')) : null;

        $query = HistoryPayments::query();

        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate->startOfDay(), $endDate->endOfDay()]);
        } elseif ($startDate) {
            $query->whereDate('date', $startDate);
        } elseif ($endDate) {
            $query->whereDate('date', $endDate);
        }

        $payments = $query->get();
        $pdf = PDF::loadView('user.pdf.payments', ['users' => $payments]);
        $fileName = 'payments_report_' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($fileName);
    }

    public function history($id)
    {
        $student = User::findOrFail($id);
        $attendances = Attendance::where('user_id', $id)->get();
        $pdf = PDF::loadView('user.pdf.student_show', ['student' => $student, 'attendances' => $attendances]);
        $fileName = 'student_history_' . $student->id . '_' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($fileName);
    }

    public function teacher()
    {
        $teachers = User::role('user')->get();
        $pdf = PDF::loadView('user.pdf.teacher', ['teacher' => $teachers]);
        $fileName = 'teachers_list_' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($fileName);
    }

    public function group()
    {
        $groups = Group::where('id', '!=', 1)->get();
        $pdf = PDF::loadView('user.pdf.group', ['group' => $groups]);
        $fileName = 'groups_list_' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($fileName);
    }

    public function student()
    {
        $students = User::role('student')->get();
        $pdf = PDF::loadView('user.pdf.student', ['student' => $students]);
        $fileName = 'students_list_' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($fileName);
    }

    public function Assessment($id)
    {
        // Assuming 'group' column in 'assessments' table stores the group name, not ID.
        // If it stores group ID, this should be changed to use the ID.
        $group = Group::findOrFail($id);
        $assessments = Assessment::where('group', $group->name)->get();
        $pdf = PDF::loadView('user.pdf.group_assessment', ['groups' => $assessments]);
        $fileName = 'group_assessment_' . $group->name . '_' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($fileName);
    }
}
