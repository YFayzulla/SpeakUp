<?php

namespace App\Http\Controllers;

use App\Http\Requests\Teacher\StoreRequest;
use App\Http\Requests\Teacher\UpdateRequest;
use App\Models\Group;
use App\Models\GroupTeacher;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = User::role('user')->orderBy('name')->get();
        return view('admin.teacher.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teacher.create', [
            'rooms' => Room::all()
        ]);
    }

    public function store(StoreRequest $request)
    {
        $path = null;
        if ($request->hasFile('photo')) {
            $fileName = time() . '.' . $request->file('photo')->getClientOriginalExtension();
            $path = $request->file('photo')->storeAs('Photo', $fileName);
        }

        DB::transaction(function () use ($request, $path) {
            $teacher = User::create([
                'name' => $request->name,
                'password' => Hash::make($request->phone), // Using phone as a default password
                'passport' => $request->passport,
                'date_born' => $request->date_born,
                'location' => $request->location,
                'phone' => '998' . preg_replace('/[^0-9]/', '', $request->phone),
                'photo' => $path,
                'percent' => $request->percent,
                'room_id' => $request->room_id
            ])->assignRole('user');

            $groups = Group::where('room_id', $request->room_id)->get();
            if ($groups->isNotEmpty()) {
                $groupTeachers = $groups->map(fn($group) => [
                    'group_id' => $group->id,
                    'teacher_id' => $teacher->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])->toArray();
                GroupTeacher::insert($groupTeachers);
            }
        });

        return redirect()->route('teacher.index')->with('success', 'Ma\'lumotlar muvaffaqiyatli qo\'shildi.');
    }

    public function show($id)
    {
        // This action is not implemented.
    }

    public function edit($id)
    {
        $teacher = User::findOrFail($id);
        $rooms = Room::all();
        return view('admin.teacher.edit', compact('teacher', 'rooms'));
    }

    public function update(UpdateRequest $request, $id)
    {
        $teacher = User::findOrFail($id);

        $path = $teacher->photo;
        if ($request->hasFile('photo')) {
            if ($teacher->photo) {
                Storage::delete($teacher->photo);
            }
            $fileName = time() . '.' . $request->file('photo')->getClientOriginalExtension();
            $path = $request->file('photo')->storeAs('Photo', $fileName);
        }

        DB::transaction(function () use ($request, $teacher, $path) {
            $updateData = [
                'name' => $request->name,
                'phone' => '998' . preg_replace('/[^0-9]/', '', $request->phone),
                'date_born' => $request->date_born,
                'location' => $request->location,
                'passport' => $request->passport,
                'percent' => $request->percent,
                'photo' => $path,
                'room_id' => $request->room_id
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $teacher->update($updateData);

            // Efficiently sync teacher's groups based on the new room
            GroupTeacher::where('teacher_id', $teacher->id)->delete();
            $groups = Group::where('room_id', $request->room_id)->get();
            if ($groups->isNotEmpty()) {
                $groupTeachers = $groups->map(fn($group) => [
                    'group_id' => $group->id,
                    'teacher_id' => $teacher->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])->toArray();
                GroupTeacher::insert($groupTeachers);
            }
        });

        return redirect()->route('teacher.index')->with('success', 'Ma\'lumotlar muvaffaqiyatli yangilandi.');
    }

    public function destroy($id)
    {
        $teacher = User::findOrFail($id);

        DB::transaction(function () use ($teacher) {
            // Delete related GroupTeacher entries
            GroupTeacher::where('teacher_id', $teacher->id)->delete();

            // Delete teacher's photo if it exists
            if ($teacher->photo) {
                Storage::delete($teacher->photo);
            }

            // Delete the teacher
            $teacher->delete();
        });

        return redirect()->back()->with('success', 'Ma\'lumotlar muvaffaqiyatli o\'chirildi.');
    }
}
