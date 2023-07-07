<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            }
        }
    }
    public function show(){
    }
    public function edit(){
        $teacher=auth()->user();
        $students = DB::select('SELECT * FROM users WHERE group_id = ?', [$teacher->id]);
        return view('admin.teachers.davomad');
    }
}
