<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $user = AUTH::user();
            $role = $user->getRoleNames();
            if ($user -> hasRole('admin') || $user -> hasRole('manager')) {
                return redirect()->route('dashboard.index');
            } elseif ($user->hasRole('user')) {
                return redirect()->route('history',\auth()->user()->id);
            }elseif ($user->hasRole('teacher')) {
                return redirect()->route('history',\auth()->user()->id);
            }
        }
    }
    public function show(){
    }

    public function attendance(){
        return view('admin.teachers.attendance');
    }
}
