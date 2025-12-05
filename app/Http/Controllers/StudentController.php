<?php

namespace App\Http\Controllers;

use App\Http\Requests\Student\StoreRequest;
use App\Http\Requests\Student\UpdateRequest;
use App\Models\Attendance;
use App\Models\DeptStudent;
use App\Models\Group;
use App\Models\StudentInformation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index()
    {
        $students = User::orderBy("name")->role('student')->get();
        return view('admin.student.index', compact('students'));
    }

    public function create()
    {
        $groups = Group::with('room')->orderBy('room_id')->get();
        return view('admin.student.create', compact('groups'));
    }

    public function store(StoreRequest $request)
    {
        $path = null;
        if ($request->hasFile('photo')) {
            $fileName = time() . '.' . $request->file('photo')->getClientOriginalExtension();
            $path = $request->file('photo')->storeAs('Photo', $fileName);
        }

        $group = Group::findOrFail($request->group_id);

        DB::transaction(function () use ($request, $group, $path) {
            $user = User::create([
                'name' => $request->name,
                'password' => Hash::make($request->phone), // Using phone as a default password
                'passport' => $request->passport,
                'phone' => $request->phone,
                'parents_name' => $request->parents_name,
                'parents_tel' => $request->parents_tel,
                'group_id' => $group->id,
                'location' => $request->location,
                'photo' => $path,
                'should_pay' => (int) $request->should_pay,
                'description' => $request->description,
                'status' => ($group->id == 1) ? null : 0,
                'room_id' => $group->room_id, // Corrected from $group->id
            ])->assignRole('student');

            StudentInformation::create([
                'user_id' => $user->id,
                'group_id' => $request->group_id,
                'group' => $group->name,
            ]);

            DeptStudent::create([
                'user_id' => $user->id,
                'payed' => 0,
                'dept' => $request->should_pay,
                'status_month' => 0
            ]);
        });

        return redirect()->route('student.index')->with('success', 'Ma\'lumotlar muvaffaqiyatli qo\'shildi.');
    }

    public function show($id)
    {
        $student = User::findOrFail($id);
        $attendances = Attendance::where('user_id', $id)->get();
        $groups = Group::all();
        return view('admin.student.show', compact('student', 'attendances', 'groups'));
    }

    public function edit($id)
    {
        $student = User::findOrFail($id);
        $groups = Group::query()->orderBy('name')->get();
        return view('admin.student.edit', compact('student', 'groups'));
    }

    public function update(UpdateRequest $request, $id)
    {
        $student = User::findOrFail($id);
        $group = Group::findOrFail($request->group_id);

        $path = $student->photo;
        if ($request->hasFile('photo')) {
            if ($student->photo) {
                Storage::delete($student->photo);
            }
            $fileName = time() . '.' . $request->file('photo')->getClientOriginalExtension();
            $path = $request->file('photo')->storeAs('Photo', $fileName);
        }

        DB::transaction(function () use ($request, $student, $group, $path) {
            if ($student->group_id != $request->group_id) {
                StudentInformation::create([
                    'user_id' => $student->id,
                    'group_id' => $request->group_id,
                    'group' => $group->name
                ]);
            }

            $updateData = [
                'name' => $request->name,
                'phone' => $request->phone,
                'passport' => $request->passport,
                'group_id' => $request->group_id,
                'parents_name' => $request->parents_name,
                'parents_tel' => $request->parents_tel,
                'location' => $request->location,
                'should_pay' => $request->should_pay,
                'photo' => $path,
                'description' => $request->description,
                'room_id' => $group->room_id,
                'status' => ($group->id == 1) ? null : $student->status,
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $student->update($updateData);

            $student->deptStudent()->updateOrCreate(
                ['user_id' => $student->id],
                ['dept' => $request->should_pay]
            );

            Attendance::where('user_id', $student->id)->update(['group_id' => $request->group_id]);
        });

        return redirect()->route('student.index')->with('success', 'Ma\'lumotlar muvaffaqiyatli yangilandi.');
    }

    public function destroy($id)
    {
        $student = User::findOrFail($id);

        DB::transaction(function () use ($student) {
            if ($student->photo) {
                Storage::delete($student->photo);
            }
            // Assuming related models have foreign key constraints with cascade delete.
            // If not, delete them manually.
            // Example:
            // StudentInformation::where('user_id', $student->id)->delete();
            // DeptStudent::where('user_id', $student->id)->delete();
            // Attendance::where('user_id', 'student->id)->delete();
            $student->delete();
        });

        return redirect()->back()->with('success', 'Ma\'lumotlar muvaffaqiyatli o\'chirildi.');
    }
}
