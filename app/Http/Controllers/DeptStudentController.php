<?php

namespace App\Http\Controllers;

use App\Models\DeptStudent;
use App\Models\HistoryPayments;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeptStudentController extends Controller
{
    /**
     * Talabalar va ularning qarzdorlik holatini ko'rsatish
     */
    public function index()
    {
        try {
            // Faqat kerakli ustunlarni olish va N+1 muammosini oldini olish
            // Agar User modelida 'deptStudent' degan relation bo'lsa, uni ham with() ga qo'shish kerak.
            // Hozircha 'group' ni qo'shdim, chunki ko'pincha guruh nomi kerak bo'ladi.
            $students = User::role('student')
                ->with('group') // Guruh nomini olish uchun (agar kerak bo'lsa)
                ->orderBy('status')
                ->orderBy('name')
                ->get();

            return view('admin.dept.index', compact('students'));
        } catch (\Exception $e) {
            Log::error('DeptStudentController@index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ma\'lumotlarni yuklashda xatolik yuz berdi.');
        }
    }

    /**
     * To'lovni amalga oshirish va bazani yangilash
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id User ID
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id)
    {
        // 1. Validatsiya
        $request->validate([
            'payment'    => 'required|numeric|min:0',
            'date_paid'  => 'nullable|date',
            'money_type' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // 2. Ma'lumotlarni olish (lockForUpdate bilan)
            // lockForUpdate - tranzaksiya tugamaguncha bu qatorni boshqalar o'zgartira olmaydi.
            $deptStudent = DeptStudent::where('user_id', $id)->lockForUpdate()->firstOrFail();
            $user        = User::with('group')->lockForUpdate()->findOrFail($id);

            $paymentAmount = $request->payment;
            $monthlyDept   = $deptStudent->dept; // Oylik to'lov miqdori
            $paidDate      = $request->date_paid ? Carbon::parse($request->date_paid) : Carbon::now();

            $remainingPayment = $paymentAmount;

            // 3. To'lov hisob-kitobi (Mantiq)

            // A) Agar oldindan qisman to'langan qismi bo'lsa, avval shuni yopamiz
            if ($deptStudent->payed > 0) {
                $neededToCompleteMonth = $monthlyDept - $deptStudent->payed;

                if ($remainingPayment >= $neededToCompleteMonth) {
                    // Oyni yopdi
                    $remainingPayment -= $neededToCompleteMonth;
                    $deptStudent->payed = 0;
                    $deptStudent->status_month++; // Qarzdorlik oyi kamaydi (yoki status oshdi)
                    $user->status++;
                } else {
                    // Oyni yopa olmadi, shunchaki qo'shib qo'yamiz
                    $deptStudent->payed += $remainingPayment;
                    $remainingPayment = 0;
                }
            }

            // B) To'liq oylarni yopish
            if ($remainingPayment >= $monthlyDept) {
                $fullMonthsPaid = floor($remainingPayment / $monthlyDept); // Necha oyga yetishi

                $deptStudent->status_month += $fullMonthsPaid;
                $user->status += $fullMonthsPaid;

                // Qolgan pulni hisoblash
                $remainingPayment -= ($fullMonthsPaid * $monthlyDept);
            }

            // C) Ortib qolgan pulni 'payed' ga yozish (keyingi oy uchun avans yoki chala)
            if ($remainingPayment > 0) {
                $deptStudent->payed += $remainingPayment;
            }

            // 4. Saqlash
            $deptStudent->date = $paidDate->format('Y-m-d');
            $deptStudent->save();
            $user->save();

            // 5. Tarixga yozish
            // $user->group null bo'lsa xato bermasligi uchun optional() yoki ?? operatori ishlatildi
            $groupName = $user->group ? $user->group->name : 'Guruhsiz';

            $paymentHistory = HistoryPayments::create([
                'user_id'       => $user->id,
                'name'          => $user->name,
                'payment'       => $paymentAmount,
                'group'         => $groupName,
                'date'          => $paidDate->format('Y-m-d'),
                'type_of_money' => $request->money_type,
            ]);

            DB::commit(); // O'zgarishlarni tasdiqlash

            // 6. Chek chiqarish (View qaytarish)
            return view('admin.pdf.chek', [
                'payment' => $paymentHistory,
                'student' => $user,
                'dept'    => $monthlyDept,
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Talaba yoki uning qarzdorlik ma\'lumotlari topilmadi.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('DeptStudentController@update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'To\'lovni amalga oshirishda tizim xatoligi yuz berdi.');
        }
    }
}