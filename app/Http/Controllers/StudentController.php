<?php

namespace App\Http\Controllers;

use App\Models\Dept;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students=User::role('user')->get();
        return view('admin.students.index',compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'tel' => 'required|string',
            'parents_tel' => 'required|string',
            'password' => 'required|string',
        ]);
        if ($request->hasFile('image')){
            $name = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('Photo',$name);
        }

        $student=User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'email_verified_at' => Carbon::now(),
            'password'=> bcrypt($request->password),
            'tel'=> $request->tel,
            'parents_tel'=> $request->parents_tel,
            'desc'=> $request->desc ?? null,
            'image'=> $path ?? null,
        ])->assignRole('user');
        $debt=Dept::create([
            'user_id'=>$student->id,
            'monthly_payment'=>$request->payment,
            'end_day'=>Carbon::now()->addDays(30),
            'manager'=>auth()->user()->name,
        ]);
        $debt->sum += $request->payment;
        $debt->save();


        return redirect()->route('student.index')->with('success','User created');


}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student=User::find($id);
        $dept=Dept::where('user_id','=',$id)->get();
        return view('admin.students.history',compact('student','dept'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        return view('admin.students.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string',
            'tel' => 'required|string',
            'email' => 'required|email'
        ]);

        $user=User::find($id);

        if ($request->hasFile('image')){
            if (isset($user->image)){
                Storage::delete($user->image);
            }
            $name = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('Photo',$name);
        }


        $user->update([
            'name' => $request->name,
            'tel' => $request->tel,
            'password' => bcrypt($request->password) ??  $user->password,
            'email' => $request->email,
            'image' => $path ?? $user->image,
        ]);
        return redirect()->route('dashboard.index')->with('success','User updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
//        $debt = Dept::where('user_id','==',$id);
        if (isset($user->image)) {
            Storage::delete($user->image);
        }
        $user->delete();
        return redirect()->back()->with('success','User deleted');
    }
}
