<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use function Illuminate\Events\queueable;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers=User::role('teacher')->get();
        $groups=Group::all();
        return view('admin.group.index',compact('teachers','groups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachers=User::role('teacher')->get();
        return view('admin.group.create',compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'start_day' => 'required|string',
            'end_day' => 'required|string',
            'teacher' => 'required',
        ]);
//        dd($request);
        Group::create([
            'name'=>$request->name,
            'end_day'=>$request->end_day,
            'start_day'=>$request->start_day,
            'teacher'=>$request->teacher_id,
        ]);
        return redirect()->route('group.index')->with('success','success');
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        //
    }
}
