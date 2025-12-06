<?php

namespace App\Http\Controllers;

use App\Exports\AttendanceExport;
use App\Models\Assessment;
use App\Models\Attendance;
use App\Models\Group;
use App\Models\StudentInformation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class GroupExtraController extends Controller
{
    /**
     * Ko'plab baholarni birdaniga o'chirish.
     */
    public function deleteMultiple(Request $request)
    {
        $request->validate(['selectedItems' => 'required|array']);

        try {
            $selectedItems = $request->input('selectedItems');

            // Xavfsizlik: Faqat raqamli ID larni ajratib olish
            $validatedItems = array_filter($selectedItems, 'is_numeric');

            if (!empty($validatedItems)) {
                // Tranzaksiya shart emas (bitta query), lekin try-catch muhim
                Assessment::whereIn('id', $validatedItems)->delete();
            }

            return redirect()->back()->with('success', 'Tanlangan elementlar muvaffaqiyatli o\'chirildi.');

        } catch (\Exception $e) {
            Log::error('GroupExtraController@deleteMultiple error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'O\'chirish jarayonida xatolik yuz berdi.');
        }
    }

    /**
     * Talabaning guruhini o'zgartirish.
     */
    public function change_group(Request $request, $id)
    {
        $request->validate(['group_id' => 'required|exists:groups,id']);

        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);
            $group = Group::findOrFail($request->group_id);

            // 1. User guruhini yangilash
            $user->update(['group_id' => $group->id]);

            // 2. Tarix (StudentInformation) yaratish
            StudentInformation::create([
                'user_id' => $user->id,
                'group_id' => $group->id,
                'group' => $group->name,
            ]);

            // 3. Eski davomatlarni yangi guruhga o'tkazish
            // (Mantiqan to'g'riligini loyiha talabidan kelib chiqib tekshiring.
            // Odatda eski davomat eski guruhda qolishi kerak, lekin sizning kodingizda o'zgartirilmoqda)
            Attendance::where('user_id', $user->id)->update(['group_id' => $group->id]);

            DB::commit();

            return redirect()->back()->with('success', 'Guruh muvaffaqiyatli o\'zgartirildi.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('GroupExtraController@change_group error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Guruhni o\'zgartirishda xatolik yuz berdi.');
        }
    }

    /**
     * Davomatni filtrlash yoki PDF hisobot chiqarish.
     */
    public function filter(Request $request, $id)
    {
        try {
            $selectedDate = $request->filled('filter_date') ? Carbon::parse($request->input('filter_date')) : Carbon::today();
            $group = Group::findOrFail($id);
            $task = $request->input('task');

            // Umumiy query
            $items = Attendance::whereDate('created_at', $selectedDate)
                ->where('group_id', $id)
                ->with('user:id,name') // Optimizatsiya: N+1 oldini olish
                ->get();

            if ($task === 'show') {
                return view('admin.group.attendance', compact('items', 'group', 'selectedDate'));
            }

            if ($task === 'report') {
                try {
                    $pdf = PDF::loadView('admin.pdf.attendance_in_group', [
                        'items' => $items,
                        'group' => $group,
                        'date' => $selectedDate
                    ]);

                    $fileName = 'attendance_report_' . $group->name . '_' . $selectedDate->format('Y-m-d') . '.pdf';
                    return $pdf->download($fileName);
                } catch (\Exception $pdfEx) {
                    Log::error('PDF Generation error: ' . $pdfEx->getMessage());
                    return redirect()->back()->with('error', 'PDF hujjatini yaratishda xatolik.');
                }
            }

            return redirect()->back()->with('error', 'Noto\'g\'ri amal tanlandi.');

        } catch (\Exception $e) {
            Log::error('GroupExtraController@filter error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ma\'lumotlarni yuklashda xatolik.');
        }
    }

    /**
     * Guruhdagi talabalar ro'yxatini ko'rsatish.
     */
    public function show($id)
    {
        try {
            $students = User::where('group_id', $id)
                ->role('student')
                // MUHIM: Guruh nomini olish uchun relationshipni yuklaymiz
                ->with('group:id,name')
                ->orderBy('name')
                // MUHIM: 'group_id' ni select ichiga qo'shish SHART, bo'lmasa relationship ishlamaydi
                ->select('id', 'name', 'email', 'image', 'phone', 'status', 'group_id')
                ->get();

            return view('admin.group.student', compact('students'));
        } catch (\Exception $e) {
            Log::error('GroupExtraController@show error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Talabalar ro\'yxatini yuklashda xatolik.');
        }
    }

    /**
     * Oylik davomat jadvalini shakllantirish (Matritsa).
     */
    public function attendance($id)
    {
        try {
            $group = Group::findOrFail($id);

            // 1. Sana validatsiyasi
            $dateInput = request('date', now()->format('Y-m'));
            try {
                $dateObj = Carbon::createFromFormat('Y-m', $dateInput);
            } catch (\Exception $e) {
                $dateObj = now();
            }

            $year = $dateObj->year;
            $month = $dateObj->month;

            // 2. Matritsa uchun talabalar ro'yxati (Ism bo'yicha)
            $students = User::role('student')
                ->where('group_id', $group->id)
                ->orderBy('name')
                ->get(['id', 'name']);

            // 3. Matritsa ichini to'ldirish uchun davomatlar (faqat status)
            $matrixAttendances = Attendance::where('group_id', $id)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->get(['user_id', 'status', 'created_at'])
                ->groupBy('user_id');

            // 4. PASTKI JADVAL UCHUN: To'liq davomat tarixi (Pagination bilan)
            // with() yordamida teacher va lesson ma'lumotlarini ham olamiz (N+1 oldini olish)
            // DIQQAT: Attendance modelida 'teacher' (who_checked) va 'lesson' relationlari bo'lishi kerak.
            $attendanceRecords = Attendance::where('group_id', $id)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->with([
                    'user:id,name',      // Talaba ismi
                    'teacher:id,name',   // Tekshirgan o'qituvchi ismi (Attendance modelida 'teacher' funksiyasi bo'lishi kerak)
                    'lesson:id,name'     // Dars mavzusi
                ])
                ->latest()
                ->paginate(20);

            $daysInMonth = $dateObj->daysInMonth;

            // 5. Matritsani shakllantirish
            $data = $students->mapWithKeys(function ($student) use ($matrixAttendances, $daysInMonth) {
                $studentAttendances = $matrixAttendances->get($student->id);
                $days = [];

                for ($i = 1; $i <= $daysInMonth; $i++) {
                    $dayStr = str_pad($i, 2, '0', STR_PAD_LEFT);
                    $days[$dayStr] = ''; // Default bo'sh
                }

                if ($studentAttendances) {
                    foreach ($studentAttendances as $attendance) {
                        $day = $attendance->created_at->format('d');
                        $days[$day] = $attendance->status;
                    }
                }
                return [$student->name => $days];
            });

            return view('admin.group.attendance', [
                'group' => $group,
                'data' => $data,         // Matritsa uchun array
                'year' => $year,
                'month' => str_pad($month, 2, '0', STR_PAD_LEFT),
                'attendanceRecords' => $attendanceRecords, // Pastki jadval uchun (YANGI)
            ]);

        } catch (\Exception $e) {
            Log::error('GroupExtraController@attendance error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Davomat jadvalini yuklashda xatolik: ' . $e->getMessage());
        }
    }

    /**
     * Excelga eksport qilish.
     */
    public function export($id)
    {
        try {
            $date = request('date', now()->format('Y-m'));
            // Sana formatini tekshirish uchun oddiy parsing
            $parts = explode('-', $date);

            if (count($parts) !== 2) {
                return redirect()->back()->with('error', 'Noto\'g\'ri sana formati.');
            }

            list($year, $month) = $parts;

            return Excel::download(new AttendanceExport($id, $year, $month), 'attendance.xlsx');

        } catch (\Exception $e) {
            Log::error('GroupExtraController@export error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Excel faylini yuklashda xatolik yuz berdi.');
        }
    }
}