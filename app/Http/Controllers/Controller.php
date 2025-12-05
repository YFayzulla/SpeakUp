<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Finance;
use App\Models\HistoryPayments;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            $today = Carbon::today();

            $totalIncome = HistoryPayments::sum('payment');
            $totalConsumption = Finance::sum('payment');
            $profit = $totalIncome - $totalConsumption;

            return view('dashboard', [
                'teachers' => User::role('user')->get(),
                'number_of_students' => User::role('student')->count(),
                'daily_income' => HistoryPayments::whereDate('created_at', $today)->sum('payment'),
                'daily_transactions' => HistoryPayments::whereDate('created_at', $today)->get(['payment', 'name']), // 'trent' renamed
                'debtor_students' => User::role('student')->where('status', '<', 0)->get(), // 'students' renamed
                'today_attendances' => Attendance::whereDate('created_at', $today)->get(), // 'attendances' renamed
                'profit' => $profit,
                'pie_chart' => [$totalIncome, $totalConsumption]
            ]);
        }

        if ($user->hasRole('student')) {
            return view('studentPage');
        }

        // For any other user, return the dashboard with empty data to prevent errors.
        return view('dashboard', [
            'teachers' => [],
            'number_of_students' => 0,
            'daily_income' => 0,
            'daily_transactions' => [],
            'debtor_students' => [],
            'today_attendances' => [],
            'profit' => 0,
            'pie_chart' => [0, 0]
        ]);
    }

    public function search(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : null;
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : null;

        $query = HistoryPayments::query();

        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('date', $startDate);
        } elseif ($endDate) {
            $query->whereDate('date', $endDate);
        }

        $historyPayments = $query->get();

        return view('admin.index', [
            'historyPayments' => $historyPayments,
            'start_date' => $startDate ? $startDate->toDateString() : null,
            'end_date' => $endDate ? $endDate->toDateString() : null,
        ]);
    }
}
