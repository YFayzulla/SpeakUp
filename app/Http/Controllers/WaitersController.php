<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;

class WaitersController extends Controller
{
    /**
     * Kutish zalidagi (Guruhsiz) talabalar ro'yxati.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        try {
            // 1. Mavjud guruhlarni olish (Kutish zali ID=1 dan tashqari) - room bilan sorted
            $groups = Group::select('id', 'name', 'room_id')
                ->where('id', '!=', 1)
                ->with('room')
                ->get()
                ->sortBy(function ($group) {
                    return $group->room ? $group->room->room : '';
                })
                ->values();

            // 2. Kutish zalidagi talabalarni olish
            // MANTIQ:
            // A) Hech qanday guruhga biriktirilmagan talabalar (doesntHave('groups'))
            // B) YOKI faqat "Waiting Room" (ID=1) guruhiga biriktirilgan talabalar
            
            $students = User::role('student')
                ->where(function (Builder $query) {
                    // A: Hech qanday guruhi yo'qlar
                    $query->doesntHave('groups')
                          // B: Yoki guruhi bor, lekin u faqat ID=1 (Waiting Room)
                          ->orWhereHas('groups', function (Builder $q) {
                              $q->where('groups.id', 1);
                          });
                })
                ->select('id', 'name', 'phone', 'parents_tel', 'created_at', 'photo')
                ->latest('created_at')
                ->paginate(20);

            return view('admin.waiters.index', compact('students', 'groups'));

        } catch (\Exception $e) {
            Log::error('WaitersController@index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Kutish zali ma\'lumotlarini yuklashda xatolik yuz berdi.');
        }
    }
}
