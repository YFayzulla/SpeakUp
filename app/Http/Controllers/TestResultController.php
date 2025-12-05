<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Group;
use App\Models\LessonAndHistory;
use Illuminate\Http\Request;

class TestResultController extends Controller
{
    public function index()
    {
        $testHistories = LessonAndHistory::where('data', 2)->paginate(10);
        $topStudents = Assessment::orderBy('get_mark', 'desc')->take(5)->get();

        return view('assessment.main', [
            'data' => $testHistories,
            'topStudents' => $topStudents
        ]);
    }

    public function showResults($historyId)
    {
        $assessments = Assessment::where('history_id', $historyId)->get();

        if ($assessments->isEmpty()) {
            // Agar natijalar topilmasa, 404 xatolik qaytarish yoki bo'sh sahifaga yo'naltirish
            abort(404, 'Baholash natijalari topilmadi.');
        }

        // Guruh nomini birinchi natijadan olish
        $groupName = $assessments->first()->group;
        
        // Guruh ID sini topish uchun qo'shimcha so'rov (agar kerak bo'lsa)
        $group = Group::where('name', $groupName)->first();

        return view('assessment.index', [
            'assessments' => $assessments,
            'groups' => Group::orderBy('name')->get(),
            'id' => $group ? $group->id : null, // View faylida guruh ID si kerak bo'lsa
            'groupName' => $groupName
        ]);
    }
}
