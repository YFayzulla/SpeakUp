<?php

namespace App\Http\Controllers;

use App\Models\Dept;
use App\Models\MonthlyPayment;
use App\Models\User;
use Illuminate\Http\Request;

class DeptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
//        dd('aslom');
        if (($id == auth()->user()->id )) {
            $student = User::find($id);
            $dept = Dept::where('user_id', '=', $id)->get();
            $money=MonthlyPayment::find(1);
            dd($money);
            return view('admin.students.history', compact('student', 'dept','money'));
        } else abort(419);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dept $dept)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dept $dept)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dept $dept)
    {
        //
    }
}
