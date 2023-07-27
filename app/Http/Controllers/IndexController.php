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
        foreach($request->status as $status=>$status){
            $attendance= new Attendance();
            $attendance['user_id'] = $status;
            $attendance['group_id'] = $request->group_id;
//            $attendance['date'] = now();
            $attendance['status'] = 1 ;
            $attendance->save();
        }
        return redirect()->back()->with('success','saved');
    }

    public function attendance_for_admin(){
        $attendances= Attendance::orderby('date')->get() ;
        return view('admin.attendance',compact('attendances'));
    }
    public function delete_attendance(Attendance $id){
        $id->delete();
        return redirect()->back()->with('success','deleted  ');
    }
    public function extra(Request $request){
        $students=User::where('status'.null)->role('user')->get();
        $admin=\auth()->user();

        if ($request->status == 1){

            $admin['status'] = 2;
            $admin->save();
            foreach ($students as $student){
                $student['status'] = 1;
                $student->save();
            }


            return redirect()->back()->with('checked','checked');

        }

        else{
            $students=User::where('status','=','1')->role('user')->get();
            $admin['status'] = 3;
            $admin->save();
            foreach ($students as $student){

                $student['status'] = null;
                $student->save();
            }

            return redirect()->back()->with('unchecked','unchecked');

        }

    }
}
