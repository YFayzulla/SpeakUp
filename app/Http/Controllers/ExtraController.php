<?php

namespace App\Http\Controllers;

use App\Models\Dept;
use App\Models\Group;
use App\Models\MonthlyPayment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class ExtraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = User::role('user')->get();
        dd($students);
        return view('admin.student.index',compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.teachers.create1');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'tel' => 'required|string',
            'password' => 'required|string',
            'email' => 'required|email',
        ]);
        if ($request->hasFile('image')){
            $name = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('Photo',$name);
        }

        User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'email_verified_at' => now(),
            'password'=> bcrypt($request->password),
            'tel'=> $request->tel,
            'desc'=> $request->desc,
            'image'=> $path ?? null,
        ])->assignRole('teacher');
        return redirect()->route('dashboard.index')->with('success','data created');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $teachers=User::role('teacher')->get();
        $students=User::role('user')->get();
        $groups=Group::all();
        return view('admin.group_student',compact('teachers','students','groups'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
//        $user = User::find($id);
//        return view('admin.teachers.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {


        $student=User::find($id);
        $dept=Dept::create([
            'user_id'=>$id,
            'sum'=>$request->payment,
            'manager'=>auth()->user()->name,
        ]);
        $monthly_payment=MonthlyPayment::find(1);
        $daily=round($monthly_payment->sum / 30);

        $student->day +=(int)(((int)$request->payment)/$daily);
//
//        $dept->end_day = Carbon::now()->addDays((int)(((int)$request->payment)/$daily));
//
//        $dept->save();

        $student->save();
        return redirect()->back()->with('success','SAVED');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
//        $user = User::findOrFail($id);
////        dd('salom');
//        if (isset($user->image)) {
//            Storage::delete($user->image);
//        }
//        $user->delete();
//        return redirect()->back()->with('success','data deleted');
    }
}
