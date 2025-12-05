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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = User::role('student')->orderBy('status')->orderBy('name')->get();
        return view('admin.dept.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // This action is not implemented.
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // This action is not implemented.
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\DeptStudent $deptStudent
     * @return \Illuminate\Http\Response
     */
    public function show(DeptStudent $deptStudent)
    {
        // This action is not implemented.
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\DeptStudent $deptStudent
     * @return \Illuminate\Http\Response
     */
    public function edit(DeptStudent $deptStudent)
    {
        // This action is not implemented.
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id User ID
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'payment' => 'required|numeric|min:0',
            'date_paid' => 'nullable|date',
            'money_type' => 'required|string|max:255',
        ]);

        $deptStudent = DeptStudent::where('user_id', $id)->firstOrFail();
        $user = User::findOrFail($id);
        $paymentAmount = $request->payment;
        $monthlyDept = $deptStudent->dept; // Assuming 'dept' is the monthly payment amount
        $paidDate = $request->date_paid ? Carbon::parse($request->date_paid) : Carbon::now();

        DB::transaction(function () use ($deptStudent, $user, $paymentAmount, $monthlyDept, $paidDate, $request) {
            $remainingPayment = $paymentAmount;

            // Handle any existing partial payment first
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

            // Process full months
            if ($remainingPayment >= $monthlyDept) {
                $fullMonthsPaid = floor($remainingPayment / $monthlyDept);
                $deptStudent->status_month += $fullMonthsPaid;
                $user->status += $fullMonthsPaid;
                $remainingPayment -= ($fullMonthsPaid * $monthlyDept);
            }

            // Handle any new partial payment
            if ($remainingPayment > 0) {
                $deptStudent->payed += $remainingPayment;
            }

            $deptStudent->date = $paidDate->format('Y-m-d');
            $deptStudent->save();
            $user->save();

            $paymentHistory = HistoryPayments::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'payment' => $paymentAmount,
                'group' => $user->group->name,
                'date' => $paidDate->format('Y-m-d'),
                'type_of_money' => $request->money_type,
            ]);

            // Return the view for the check/receipt
            // Note: Returning a view directly from an update method is unusual.
            // Typically, you would redirect and then generate the PDF on a separate route.
            // Keeping it as is for now, but it's a point for future refactoring.
            return view('admin.pdf.chek', [
                'payment' => $paymentHistory,
                'student' => $user,
                'dept' => $monthlyDept,
            ]);
        });

        // If the transaction fails or no view is returned from the transaction,
        // this redirect will act as a fallback.
        return redirect()->back()->with('success', 'To\'lov muvaffaqiyatli amalga oshirildi.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\DeptStudent $deptStudent
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeptStudent $deptStudent)
    {
        // This action is not implemented.
    }
}
