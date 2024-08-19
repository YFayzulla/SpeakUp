<?php

namespace App\Http\Controllers;

use App\Models\GroupTeacher;
use App\Models\HistoryPayments;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{


    public function index()
    {

        if (auth()->user()->hasRole('admin')) {
            $teachers = User::query()->role('user')->get();


            return view('dashboard', [
                    'teachers' => $teachers,
                    'students' => User::query()->role('student')->count(),
                    'daily_profit' => HistoryPayments::query()->whereDate('created_at', today())->sum('payment'),
                ]
            );
        }
        else
            return view('dashboard');

    }
}