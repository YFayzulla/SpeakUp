<?php

namespace App\Http\Controllers;

use App\Http\Requests\Teacher\StoreRequest;
use App\Http\Requests\Teacher\UpdateRequest;
use App\Models\Group;
use App\Models\GroupTeacher;
use App\Models\Level;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $teachers = User::role('user')->orderBy('name')->get();
        return view('admin.teacher.index', compact('teachers'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {

        return view('admin.teacher.create',[
            'rooms' => Room::all()
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        if ($request->hasFile('photo')) {
            $fileName = time() . '.' . $request->file('photo')->getClientOriginalExtension();
            $path = $request->file('photo')->storeAs('Photo', $fileName);

        }

        $teacher = User::create([
            'name' => $request->name,
            'password' => bcrypt($request->name),
            'passport' => $request->passport,
            'date_born' => $request->date_born,
            'location' => $request->location,
            'phone' => 998 . $request->phone,
            'photo' => $path ?? null,
            'percent' => $request->percent,
            'room_id' => $request->room_id
        ])->assignRole('user');

        $index = GroupTeacher::insert(
            Group::where('room_id', $request->room_id)
                ->get()
                ->map(fn($group) => [
                    'group_id' => $group->id,
                    'teacher_id' => $teacher->id,
                ])
                ->toArray()
        );

        return redirect()->route('teacher.index')->with('success', 'Information has been added');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $teacher = User::find($id);
        $rooms = Room::all();
        if ($teacher !== null)
            return view('admin.teacher.edit', compact('teacher', 'rooms'));
        else
            return abort('403');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        $teacher = User::find($id);

        GroupTeacher::where('teacher_id', $teacher->id)->get()->each->delete();

        if ($request->hasFile('photo')) {
            if (isset($teacher->photo)) {
                Storage::delete($teacher->photo);
            }
            $fileName = time() . '.' . $request->file('photo')->getClientOriginalExtension();
            $path = $request->file('photo')->storeAs('Photo', $fileName);
        };

        $teacher->update([
            'name' => $request->name,
            'phone' => 998 . $request->phone,
            'password' => bcrypt($request->password),
            'date_born' => $request->date_born,
            'location' => $request->location,
            'passport' => $request->passport,
            'percent' => $request->percent,
            'photo' => $path ?? $teacher->photo ?? null,
            'room_id' => $request->room_id
        ]);

        $index = GroupTeacher::insert(
            Group::where('room_id', $request->room_id)
                ->get()
                ->map(fn($group) => [
                    'group_id' => $group->id,
                    'teacher_id' => $teacher->id,
                ])
                ->toArray()
        );


        return redirect()->route('teacher.index')->with('success', 'Information has been updated');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        GroupTeacher::where('teacher_id', $id)->get()->each->delete();

        $teacher = User::find($id);
        $teacher->delete();
        return redirect()->back()->with('success', 'Information deleted');
    }
}
