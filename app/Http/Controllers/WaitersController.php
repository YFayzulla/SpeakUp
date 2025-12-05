<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class WaitersController extends Controller
{
    /**
     * Display a listing of students in the waiting list and available groups.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch all groups except the "waiting list" group (assumed to have id=1)
        $groups = Group::where('id', '!=', 1)->get();

        // Fetch all students who are in the "waiting list" group (group_id=1)
        $students = User::role('student')
            ->where('group_id', 1)
            ->orderBy('name')
            ->get();

        return view('admin.waiters.index', compact('students', 'groups'));
    }
}
