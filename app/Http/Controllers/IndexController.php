<?php

namespace App\Http\Controllers;
use App\Models\Attendance;
use App\Models\Group;
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
                return redirect()->route('history',auth()->user()->id);
            }elseif ($user->hasRole('teacher')) {
                return redirect()->route('index.attendance',auth()->user()->id);
            }
        }
    }
    public function show(){
    }

    public function attendance($id){
        $user=User::find($id);
        $groups=Group::where('teacher_id','=',$id)->get();
        $students=User::role('user')->get();
//        dd($user);
        return view('admin.teachers.attendance',compact('user','students','groups'));
    }

    public function store(Request $request){
//        $attendance= new Attendance();
//        $attendance-
    }
}
