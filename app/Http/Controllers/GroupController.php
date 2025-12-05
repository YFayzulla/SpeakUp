<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupTeacher;
use App\Models\Room;
// use App\Models\StudentInformation; // Removed as its usage in update() was problematic
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Added for transaction

class GroupController extends Controller
{
    public function index()
    {
        $rooms = Room::all(); // Fetch all rooms
        return view('admin.group.room', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // This action is typically used to display a form for creating a new resource.
        // The form is rendered by the makeGroup method in this controller.
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'finish_time' => 'required|date_format:H:i|after:start_time',
            'monthly_payment' => 'required|numeric|min:0',
            'room' => 'required|exists:rooms,id',
        ]);

        $group = Group::create([
            'name' => $request->name,
            'start_time' => $request->start_time,
            'finish_time' => $request->finish_time,
            'monthly_payment' => (int) $request->monthly_payment,
            'room_id' => $request->room,
        ]);

        // Assuming hasTeacher() method on Group model returns a teacher_id if a teacher is associated.
        // If this logic is complex, consider moving it to a service or observer.
        if ($group->hasTeacher()) {
            GroupTeacher::create([
                'group_id' => $group->id,
                'teacher_id' => $group->hasTeacher(),
            ]);
        }

        return redirect()->route('group.show', $group->room_id)->with('success', 'Guruh muvaffaqiyatli qo\'shildi.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id The room ID.
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Assuming '1' is a special group ID (e.g., unassigned students) that should not be displayed here.
        $groups = Group::where('id', '!=', 1)
                        ->where('room_id', $id)
                        ->orderBy('start_time')
                        ->get();

        return view('admin.group.index', compact('groups', 'id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        $rooms = Room::query()->orderBy('room')->get();
        return view('admin.group.edit', compact('group', 'rooms'));
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
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'finish_time' => 'required|date_format:H:i|after:start_time',
            'monthly_payment' => 'required|numeric|min:0',
            'room' => 'required|exists:rooms,id',
        ]);

        $group->update([
            'name' => $request->name,
            'start_time' => $request->start_time,
            'finish_time' => $request->finish_time,
            'monthly_payment' => $request->monthly_payment,
            'room_id' => $request->room,
        ]);

        // --- MUHIM ESLATMA: StudentInformation modelining maqsadi va ishlatilishi aniqlashtirilishi kerak. ---
        // Quyidagi kod guruh yangilanganda, guruhdagi har bir talaba uchun yangi StudentInformation yozuvini yaratardi.
        // Bu ma'lumotlar bazasida takrorlanishlarga olib keladi va juda samarasiz.
        // Agar StudentInformation talabaning joriy guruhini kuzatishi kerak bo'lsa, mavjud yozuvlar yangilanishi kerak, yangilari yaratilmasligi kerak.
        // Agar u tarixiy ma'lumotlarni kuzatishi kerak bo'lsa, u talaba guruhga qo'shilganda bir marta yaratilishi kerak, guruh yangilanganda emas.
        // Hozircha, bu kod izohga olindi. Iltimos, StudentInformation modelining maqsadini aniqlang va uning yaratilishi/yangilanishi mantiqini to'g'rilang.
        /*
        foreach ($group->users as $student) { // Changed $group->users() to $group->users for relationship access
            StudentInformation::create([
                'user_id' => $student->id,
                'group_id' => $group->id,
                'group' => $group->name // This 'group' field is redundant if group_id is present.
            ]);
        }
        */

        return redirect()->back()->with('success', 'Ma\'lumotlar muvaffaqiyatli yangilandi.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        DB::transaction(function () use ($group) {
            // Delete associated GroupTeacher records
            GroupTeacher::where('group_id', $group->id)->delete(); // Use delete() directly on query builder

            // Update users' group_id to a default group (assuming 1 is the default/unassigned group ID)
            User::where('group_id', $group->id)->update(['group_id' => 1]);

            // Delete the group
            $group->delete();
        });

        return redirect()->back()->with('success', 'Guruh muvaffaqiyatli o\'chirildi.');
    }

    public function makeGroup($id)
    {
        // $id here is expected to be room_id
        return view('admin.group.create', compact('id'));
    }
}
