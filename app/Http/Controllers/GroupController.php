<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupTeacher;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GroupController extends Controller
{
    /**
     * Barcha xonalarni ko'rsatish (Rooms list).
     */
    public function index()
    {
        try {
            // Faqat kerakli ustunlarni olish
            $rooms = Room::orderBy('room')->get();
            return view('admin.group.room', compact('rooms'));
        } catch (\Exception $e) {
            Log::error('GroupController@index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Xonalarni yuklashda xatolik yuz berdi.');
        }
    }

    /**
     * Guruh yaratish sahifasi.
     *
     * @param int $id Room ID
     */
    public function makeGroup($id)
    {
        try {
            // Xona mavjudligini tekshirish (ixtiyoriy, lekin foydali)
            if (!Room::find($id)) {
                return redirect()->back()->with('error', 'Tanlangan xona topilmadi.');
            }
            return view('admin.group.create', compact('id'));
        } catch (\Exception $e) {
            Log::error('GroupController@makeGroup error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Sahifani yuklashda xatolik.');
        }
    }

    /**
     * Yangi guruhni saqlash.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'start_time'      => 'required|date_format:H:i',
            'finish_time'     => 'required|date_format:H:i|after:start_time',
            'monthly_payment' => 'required|numeric|min:0',
            'room'            => 'required|exists:rooms,id',
        ]);

        DB::beginTransaction();

        try {
            $group = Group::create([
                'name'            => $request->name,
                'start_time'      => $request->start_time,
                'finish_time'     => $request->finish_time,
                'monthly_payment' => (int) $request->monthly_payment,
                'room_id'         => $request->room,
            ]);

            // Agar guruh modelida hasTeacher() metodi bo'lsa va u ID qaytarsa
            // (Bu mantiq sizning modelingizda borligiga tayandim)
            if (method_exists($group, 'hasTeacher')) {
                $teacherId = $group->hasTeacher();
                if ($teacherId) {
                    GroupTeacher::create([
                        'group_id'   => $group->id,
                        'teacher_id' => $teacherId,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('group.show', $group->room_id)
                ->with('success', 'Guruh muvaffaqiyatli qo\'shildi.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('GroupController@store error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Guruhni saqlashda xatolik yuz berdi.');
        }
    }

    /**
     * Muayyan xonadagi guruhlarni ko'rsatish.
     *
     * @param int $id Room ID
     */
    public function show($id)
    {
        try {
            // '1' ID li guruh (odatda "Guruhsizlar") ko'rsatilmasligi kerak
            $groups = Group::where('id', '!=', 1)
                ->where('room_id', $id)
                ->orderBy('start_time')
                ->get();

            return view('admin.group.index', compact('groups', 'id'));

        } catch (\Exception $e) {
            Log::error('GroupController@show error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Guruhlarni yuklashda xatolik.');
        }
    }

    /**
     * Guruhni tahrirlash sahifasi.
     */
    public function edit(Group $group)
    {
        try {
            $rooms = Room::orderBy('room')->get();
            return view('admin.group.edit', compact('group', 'rooms'));
        } catch (\Exception $e) {
            Log::error('GroupController@edit error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Tahrirlash sahifasini ochishda xatolik.');
        }
    }

    /**
     * Guruh ma'lumotlarini yangilash.
     */
    public function update(Request $request, Group $group)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'start_time'      => 'required|date_format:H:i',
            'finish_time'     => 'required|date_format:H:i|after:start_time',
            'monthly_payment' => 'required|numeric|min:0',
            'room'            => 'required|exists:rooms,id',
        ]);

        DB::beginTransaction();

        try {
            $group->update([
                'name'            => $request->name,
                'start_time'      => $request->start_time,
                'finish_time'     => $request->finish_time,
                'monthly_payment' => $request->monthly_payment,
                'room_id'         => $request->room,
            ]);

            // Eslatma: StudentInformation bo'yicha kod olib tashlandi.
            // Sababi: Guruh ma'lumoti o'zgarganda talabaning tarixi yaratilishi mantiqan noto'g'ri.
            // Talaba tarixi faqat talaba guruhga qo'shilganda yoki guruhdan chiqqanda yozilishi kerak.

            DB::commit();

            return redirect()->back()->with('success', 'Ma\'lumotlar muvaffaqiyatli yangilandi.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('GroupController@update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Yangilashda xatolik yuz berdi.');
        }
    }

    /**
     * Guruhni o'chirish.
     */
    public function destroy(Group $group)
    {
        DB::beginTransaction();

        try {
            // 1. Guruh o'qituvchilari bog'lanishini o'chirish
            GroupTeacher::where('group_id', $group->id)->delete();

            // 2. Guruhdagi talabalarni 'Guruhsiz' (ID: 1) holatiga o'tkazish
            // DIQQAT: Tizimda ID=1 bo'lgan guruh borligiga ishonch hosil qiling.
            User::where('group_id', $group->id)->update(['group_id' => 1]);

            // 3. Guruhni o'chirish
            $group->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Guruh muvaffaqiyatli o\'chirildi.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('GroupController@destroy error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Guruhni o\'chirishda xatolik yuz berdi.');
        }
    }
}