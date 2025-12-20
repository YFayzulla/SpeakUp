<?php

namespace App\Http\Controllers;

use App\Models\DeptStudent;
use App\Models\HistoryPayments;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeptStudentController extends Controller
{
    public function index()
    {
        $students = User::role('student')
            ->with('deptStudent', 'group')
            // Use a subquery to fetch the current payed value deterministically for sorting,
            // avoiding any duplicate rows or mismatched joins
            ->select('users.*')
            ->selectRaw('(SELECT payed FROM dept_students WHERE dept_students.user_id = users.id ORDER BY id DESC LIMIT 1) AS payed_for_sort')
            // Order groups explicitly:
            // 0) Partially Paid (active)
            // 1) Debtor (status <= 0, active)
            // 2) Paid (status > 0, active)
            // 3) Disabled (status IS NULL)
            ->orderByRaw("CASE WHEN users.status IS NULL THEN 3 WHEN COALESCE(payed_for_sort, 0) > 0 THEN 0 WHEN users.status <= 0 THEN 1 ELSE 2 END")
            // Inside groups, sort by status then by name
            ->orderBy('users.status')
            ->orderBy('users.name')
            ->get();

        return view('admin.dept.index', compact('students'));
    }

    public function create()
    {
        // This action is not implemented.
    }

    public function store(Request $request)
    {
        // This action is not implemented.
    }

    public function show(DeptStudent $deptStudent)
    {
        // This action is not implemented.
    }

    public function edit(DeptStudent $deptStudent)
    {
        // This action is not implemented.
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'payment' => 'required|string',
            'date_paid' => 'nullable|date',
            'money_type' => 'required|string|max:255',
        ]);

        $cleanPayment = (float) str_replace([' ', ','], '', $request->payment);

        if ($cleanPayment <= 0) {
            return redirect()->back()->with('error', 'To\'lov miqdori noto\'g\'ri kiritildi.');
        }

        $user = User::with('deptStudent', 'group')->findOrFail($id);
        $deptStudent = $user->deptStudent;

        if (!$deptStudent) {
            return redirect()->back()->with('error', 'Talabaning to\'lov ma\'lumotlari topilmadi.');
        }

        $monthlyDept = $deptStudent->dept;
        $paidDate = $request->date_paid ? Carbon::parse($request->date_paid) : Carbon::now();
        
        $paymentHistoryId = null;

        DB::transaction(function () use ($deptStudent, $user, $cleanPayment, $monthlyDept, $paidDate, $request, &$paymentHistoryId) {
            $remainingPayment = $cleanPayment;

            if ($deptStudent->payed > 0) {
                $neededToCompleteMonth = $monthlyDept - $deptStudent->payed;
                if ($remainingPayment >= $neededToCompleteMonth) {
                    $remainingPayment -= $neededToCompleteMonth;
                    $deptStudent->payed = 0;
                    $deptStudent->status_month++;
                    $user->status++;
                } else {
                    $deptStudent->payed += $remainingPayment;
                    $remainingPayment = 0;
                }
            }

            if ($monthlyDept > 0 && $remainingPayment >= $monthlyDept) {
                $fullMonthsPaid = floor($remainingPayment / $monthlyDept);
                $deptStudent->status_month += $fullMonthsPaid;
                $user->status += $fullMonthsPaid;
                $remainingPayment -= ($fullMonthsPaid * $monthlyDept);
            }

            if ($remainingPayment > 0) {
                $deptStudent->payed += $remainingPayment;
            }

            $deptStudent->date = $paidDate->format('Y-m-d');
            $deptStudent->save();
            $user->save();

            $paymentHistory = HistoryPayments::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'payment' => $cleanPayment,
                'group' => $user->group->name,
                'date' => $paidDate->format('Y-m-d'),
                'type_of_money' => $request->money_type,
            ]);
            
            $paymentHistoryId = $paymentHistory->id;
        });

        // Redirect back with a success message and the ID of the payment history for the receipt
        return redirect()->back()
            ->with('success', 'To\'lov muvaffaqiyatli amalga oshirildi.')
            ->with('payment_receipt_id', $paymentHistoryId);
    }

    /**
     * Generate and show a payment receipt (check).
     *
     * @param int $paymentId
     * @return \Illuminate\View\View
     */
    public function showReceipt(int $paymentId)
    {
        $payment = HistoryPayments::with(['user.group.room'])->findOrFail($paymentId);
        $student = $payment->user;
        $monthlyDept = $student->deptStudent->dept ?? $student->should_pay;

        return view('admin.pdf.chek', [
            'payment' => $payment,
            'student' => $student,
            'dept' => $monthlyDept,
        ]);
    }

    public function destroy(DeptStudent $deptStudent)
    {
        // This action is not implemented.
    }
}