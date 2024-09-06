<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Group;
use App\Models\GroupTeacher;
use App\Models\Level;
use App\Models\Room;
use App\Models\StudentInformation;
use App\Models\User;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        return view('user.group.room', [
//            'rooms' => Room::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

//        dd($request);
        $request->validate([
            'name' => 'required',
            'monthly_payment' => 'required',
        ]);

        $group = Group::create([
            'name' => $request->name,
            'start_time' => $request->start_time,
            'finish_time' => $request->finish_time,
            'monthly_payment' => $request->monthly_payment,
            'room_id' => $request->room,
        ]);


        if ($group->hasTeacher()) {
            GroupTeacher::create([
                'group_id' => $group->id,
                'teacher_id' => $group->hasTeacher(),
            ]);
        }

        return redirect()->route('group.show', $group->room_id)->with('success', 'Information has been added');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $groups = Group::where('id', '!=', 1)->where('room_id', $id)->orderby('start_time')->get();
        return view('user.group.index', compact('groups', 'id'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        $rooms = Room::all();
        return view('user.group.edit', compact('group', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        $request->validate([
            'name' => 'required',
            'monthly_payment' => 'required',
        ]);



        dd($request->all());
        $group->update([
            'name' => $request->name,
            'start_time' => $request->start_time,
            'finish_time' => $request->finish_time,
            'monthly_payment' => $request->monthly_payment,
            'room_id' => $request->room,
        ]);

//        dd($group);

        return redirect()->back()->with('success', 'Information has been updated');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {

        GroupTeacher::where('group_id', $group->id)->get()->each->delete();
        $group->delete();
        User::where('group_id', $group->id)->update(['group_id' => 1]);// Assuming 1 is the default group ID
        return redirect()->back()->with('success', 'Information deleted');

    }

    public function makeGroup($id)
    {
        return view('user.group.create', compact('id'));

    }

}
