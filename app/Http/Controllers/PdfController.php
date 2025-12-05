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
use PDF; // Barryvdh\DomPDF\Facade\Pdf ishlatilmoqda deb taxmin qilindi

class PdfController extends Controller
{
    public function __construct()
    {
        // PDF yaratish ko'p vaqt olishi mumkin, shuning uchun vaqt limitini oshiramiz
        set_time_limit(300);
        // Xotira limitini ham vaqtincha oshirish foydali bo'lishi mumkin
        ini_set('memory_limit', '256M');
    }

    /**
     * To'lovlar tarixini PDF qilib yuklash.
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

            // Faqat kerakli ustunlarni tanlab olish
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
     * Talabaning shaxsiy tarixi va davomati.
     */
    public function history($id)
    {
        try {
            $student = User::select('id', 'name', 'email', 'phone', 'group_id')
                ->with('group:id,name') // Guruh nomini olish uchun
                ->findOrFail($id);

            $attendances = Attendance::where('user_id', $id)
                ->select('created_at', 'status', 'user_id') // Kerakli ustunlar
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
     * O'qituvchilar ro'yxati PDF.
     */
    public function teacher()
    {
        try {
            // Faqat kerakli ustunlarni olish
            $teachers = User::role('user')
                ->select('id', 'name', 'email', 'phone')
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
     * Guruhlar ro'yxati PDF.
     */
    public function group()
    {
        try {
            $groups = Group::where('id', '!=', 1)
                ->select('id', 'name', 'start_time', 'finish_time', 'monthly_payment', 'room_id')
                ->with('room:id,room') // Xona nomini olish (agar Room modelida room ustuni bo'lsa)
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
     * Talabalar ro'yxati PDF.
     */
    public function student()
    {
        try {
            // Memory Limit Error bermasligi uchun faqat kerakli ustunlar olinadi
            $students = User::role('student')
                ->select('id', 'name', 'phone', 'group_id', 'status')
                ->with('group:id,name') // N+1 ni oldini olish
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
     * Guruh baholari (Assessment) PDF.
     */
    public function Assessment($id)
    {
        try {
            $group = Group::findOrFail($id);

            // Assessment jadvalida 'group' ustuni String (name) saqlaydi deb taxmin qilindi.
            // Agar ID saqlasa, ->where('group_id', $group->id) ga o'zgartirish kerak.
            $assessments = Assessment::where('group', $group->name)
                ->with('user:id,name') // Talaba ismini olish uchun (N+1 oldini olish)
                ->get();

            $pdf = PDF::loadView('user.pdf.group_assessment', ['groups' => $assessments]);
            $fileName = 'group_assessment_' . $group->name . '_' . now()->format('Y-m-d') . '.pdf';

            return $pdf->download($fileName);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Guruh topilmadi.');
        } catch (\Exception $e) {
            Log::error('PdfController@Assessment error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Baho hisobotini yuklashda xatolik.');
        }
    }
}