<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Attendance;
use App\Models\Group;
use App\Models\HistoryPayments;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PDF; // Assuming Barryvdh\DomPDF\Facade\Pdf is used

class PdfController extends Controller
{
    public function __construct()
    {
        // Increase execution time and memory limit for PDF generation
        set_time_limit(300);
        ini_set('memory_limit', '256M');
    }

    /**
     * Download payment history as PDF.
     */
    public function RoomListPDF(Request $request)
    {
        $request->validate([
            'startDate' => 'nullable|date',
            'endDate'   => 'nullable|date',
        ]);

        try {
            $startDate = $request->input('startDate') ? Carbon::parse($request->input('startDate')) : null;
            $endDate   = $request->input('endDate') ? Carbon::parse($request->input('endDate')) : null;

            $query = HistoryPayments::select('id', 'name', 'payment', 'date', 'group', 'type_of_money');

            if ($startDate && $endDate) {
                $query->whereBetween('date', [$startDate->startOfDay(), $endDate->endOfDay()]);
            } elseif ($startDate) {
                $query->whereDate('date', $startDate);
            } elseif ($endDate) {
                $query->whereDate('date', $endDate);
            }

            $payments = $query->latest('date')->get();

            $pdf = PDF::loadView('user.pdf.payments', ['users' => $payments]);
            $fileName = 'payments_report_' . now()->format('Y-m-d_H-i') . '.pdf';

            return $pdf->download($fileName);

        } catch (\Exception $e) {
            Log::error('PdfController@RoomListPDF error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'PDF hisobotini yaratishda xatolik yuz berdi.');
        }
    }

    /**
     * Student's personal history and attendance.
     */
    public function history($id)
    {
        try {
            $student = User::select('id', 'name', 'phone', 'group_id')
                ->with('group:id,name') // Eager load group name
                ->findOrFail($id);

            $attendances = Attendance::where('user_id', $id)
                ->select('created_at', 'status')
                ->latest()
                ->get();

            $pdf = PDF::loadView('user.pdf.student_show', [
                'student'     => $student,
                'attendances' => $attendances
            ]);

            $fileName = 'student_history_' . $student->id . '_' . now()->format('Y-m-d') . '.pdf';

            return $pdf->download($fileName);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Talaba topilmadi.');
        } catch (\Exception $e) {
            Log::error('PdfController@history error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Talaba tarixini yuklashda xatolik.');
        }
    }

    /**
     * Teachers list PDF.
     */
    public function teacher()
    {
        try {
            $teachers = User::role('user')
                ->select('id', 'name', 'phone')
                ->orderBy('name')
                ->get();

            $pdf = PDF::loadView('user.pdf.teacher', ['teacher' => $teachers]);
            $fileName = 'teachers_list_' . now()->format('Y-m-d') . '.pdf';

            return $pdf->download($fileName);
        } catch (\Exception $e) {
            Log::error('PdfController@teacher error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'O\'qituvchilar ro\'yxatini yuklashda xatolik.');
        }
    }

    /**
     * Groups list PDF.
     */
    public function group()
    {
        try {
            $groups = Group::where('id', '!=', 1)
                ->select('id', 'name', 'start_time', 'finish_time', 'monthly_payment', 'room_id')
                ->with('room:id,room') // Eager load room name
                ->orderBy('name')
                ->get();

            $pdf = PDF::loadView('user.pdf.group', ['group' => $groups]);
            $fileName = 'groups_list_' . now()->format('Y-m-d') . '.pdf';

            return $pdf->download($fileName);
        } catch (\Exception $e) {
            Log::error('PdfController@group error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Guruhlar ro\'yxatini yuklashda xatolik.');
        }
    }

    /**
     * Students list PDF.
     */
    public function student()
    {
        try {
            $students = User::role('student')
                ->select('id', 'name', 'phone', 'group_id', 'status')
                ->with('group:id,name') // Prevent N+1 problem
                ->orderBy('name')
                ->get();

            $pdf = PDF::loadView('user.pdf.student', ['student' => $students]);
            $fileName = 'students_list_' . now()->format('Y-m-d') . '.pdf';

            return $pdf->download($fileName);
        } catch (\Exception $e) {
            Log::error('PdfController@student error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Talabalar ro\'yxatini yuklashda xatolik.');
        }
    }

    /**
     * Group assessment PDF.
     */
    public function Assessment($id)
    {
        try {
            // Assuming 'assessments' table has a 'group_id' column.
            // If it stores the group name, the logic needs to be different, but using ID is better practice.
            $assessments = Assessment::where('group_id', $id)
                ->with('user:id,name') // Eager load student name
                ->get();

            if ($assessments->isEmpty()) {
                 // Try to find by group name as a fallback
                 $group = Group::find($id);
                 if($group) {
                    $assessments = Assessment::where('group', $group->name)->with('user:id,name')->get();
                 }
                 if ($assessments->isEmpty()) {
                    return redirect()->back()->with('warning', 'Ushbu guruh uchun baholash natijalari topilmadi.');
                 }
            }
            
            $groupName = $assessments->first()->group; // Get group name from the first record

            $pdf = PDF::loadView('user.pdf.group_assessment', ['groups' => $assessments, 'groupName' => $groupName]);
            $fileName = 'group_assessment_' . str_replace(' ', '_', $groupName) . '_' . now()->format('Y-m-d') . '.pdf';

            return $pdf->download($fileName);

        } catch (\Exception $e) {
            Log::error('PdfController@Assessment error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Baho hisobotini yuklashda xatolik.');
        }
    }
}
