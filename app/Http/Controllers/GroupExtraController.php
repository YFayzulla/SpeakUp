<?php

namespace App\Http\Controllers;

use App\Exports\AttendanceExport;
use App\Models\Assessment;
use App\Models\Attendance;
use App\Models\Group;
use App\Models\StudentInformation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class GroupExtraController extends Controller
{
    public function deleteMultiple(Request $request)
    {
        $request->validate(['selectedItems' => 'required|array']);
        $selectedItems = $request->input('selectedItems');

        // Ensure all items are numeric to prevent potential issues
        $validatedItems = array_filter($selectedItems, 'is_numeric');

        if (!empty($validatedItems)) {
            Assessment::whereIn('id', $validatedItems)->delete();
        }

        return redirect()->back()->with('success', 'Tanlangan elementlar muvaffaqiyatli o\'chirildi.');
    }

    public function change_group(Request $request, $id)
    {
        $request->validate(['group_id' => 'required|exists:groups,id']);

        $user = User::findOrFail($id);
        $group = Group::findOrFail($request->group_id);

        DB::transaction(function () use ($user, $group) {
            $user->update(['group_id' => $group->id]);

            StudentInformation::create([
                'user_id' => $user->id,
                'group_id' => $group->id,
                'group' => $group->name,
            ]);

            Attendance::where('user_id', $user->id)->update(['group_id' => $group->id]);
        });

        return redirect()->back()->with('success', 'Guruh muvaffaqiyatli o\'zgartirildi.');
    }

    public function filter(Request $request, $id)
    {
        $selectedDate = $request->filled('filter_date') ? Carbon::parse($request->input('filter_date')) : Carbon::today();
        $group = Group::findOrFail($id);

        if ($request->input('task') === 'show') {
            $items = Attendance::whereDate('created_at', $selectedDate)->where('group_id', $id)->get();
            return view('admin.group.attendance', compact('items', 'group', 'selectedDate'));
        }

        if ($request->input('task') === 'report') {
            $items = Attendance::whereDate('created_at', $selectedDate)->where('group_id', $id)->get();
            $pdf = PDF::loadView('admin.pdf.attendance_in_group', ['items' => $items, 'group' => $group, 'date' => $selectedDate]);
            $fileName = 'attendance_report_' . $group->name . '_' . $selectedDate->format('Y-m-d') . '.pdf';
            return $pdf->download($fileName);
        }

        return redirect()->back()->with('error', 'Noto\'g\'ri amal tanlandi.');
    }

    public function show($id)
    {
        $students = User::where('group_id', $id)->orderBy('name')->role('student')->get();
        return view('admin.group.student', compact('students'));
    }

    public function attendance($id)
    {
        $group = Group::findOrFail($id);
        $date = request('date', now()->format('Y-m'));
        list($year, $month) = explode('-', $date);

        $students = User::role('student')->where('group_id', $group->id)->orderBy('name')->get();
        
        // Eager load user to avoid N+1 problem
        $attendances = Attendance::with('user')
            ->where('group_id', $id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get()
            ->groupBy('user_id');

        $data = $students->mapWithKeys(function ($student) use ($attendances, $month, $year) {
            $studentAttendances = $attendances->get($student->id);
            $days = [];
            $daysInMonth = Carbon::createFromDate($year, $month)->daysInMonth;

            for ($i = 1; $i <= $daysInMonth; $i++) {
                $day = str_pad($i, 2, '0', STR_PAD_LEFT);
                $days[$day] = ''; // Default status
            }

            if ($studentAttendances) {
                foreach ($studentAttendances as $attendance) {
                    $day = $attendance->created_at->format('d');
                    $days[$day] = $attendance->status;
                }
            }
            return [$student->name => $days];
        });

        return view('admin.group.attendance', [
            'group' => $group,
            'data' => $data,
            'year' => $year,
            'month' => $month,
            'students' => $students, // Pass students for the view to iterate
        ]);
    }

    public function export($id)
    {
        $date = request('date', now()->format('Y-m'));
        list($year, $month) = explode('-', $date);

        return Excel::download(new AttendanceExport($id, $year, $month), 'attendance.xlsx');
    }
}
