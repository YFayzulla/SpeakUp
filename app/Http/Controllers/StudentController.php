<?php

namespace App\Http\Controllers;


use App\Models\Attendance;
use App\Models\Dept;
use App\Models\Group;
use App\Models\MonthlyPayment;
use App\Models\User;
use DateTime;
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
        $students=User::role('user')->orderby('day')->paginate(20);
        return view('admin.students.index',compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groups=Group::all();
        return view('admin.students.create',compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $monthly_payment=MonthlyPayment::find(1);
        $daily=round($monthly_payment->sum / 30);

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'tel' => 'required|string',
//            'parents_tel' => 'required|string',
            'password' => 'required|string',
        ]);
        if ($request->hasFile('image')) {
            $name = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('Photo', $name);
        }
        $student=User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'email_verified_at' => Carbon::now(),
            'password'=> bcrypt($request->password),
            'tel'=> $request->tel,
            'parents_tel'=> $request->parents_tel,
            'group_id'=> $request->group_id,
            'desc'=> $request->desc ?? null,
            'image'=> $path ?? null,
        ])->assignRole('user');

        $pay=Dept::create([
           'manager'=>auth()->user()->name,
            'user_id'=>$student->id,
            'sum'=>$request->sum
        ]);

        $student->day = round($request->sum / $daily)   ;

//
//        $pay->day = $pay->create_at->addDays(round($request->sum / $daily) +1);

        $student->save();
//        $pay->save();//

        return redirect()->route('student.index')->with('success','User created');


}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student=User::find($id);
        $dept=Dept::where('user_id','=',$id)->get();
        $attendances=Attendance::where('user_id','=',$id)->get();
        $money=MonthlyPayment::find(1);
        return view('admin.students.history',compact('student','dept','attendances','money'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        $groups = Group::all();
        return view('admin.students.edit',compact('user','groups'));
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
            'desc' => $request->desc,
            'group_id'=> $request->group_id,
            'image' => $path ?? $user->image ?? null,
            'status'=>$request->status,
        ]);
        return redirect()->route('student.index')->with('success','User updated');
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
