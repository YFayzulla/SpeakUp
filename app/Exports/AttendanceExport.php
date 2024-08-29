<?php

namespace App\Exports;

use App\Models\Group;
use App\Models\User;
use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $groupId;
    protected $year;
    protected $month;

    public function __construct($groupId, $year, $month)
    {
        $this->groupId = $groupId;
        $this->year = $year;
        $this->month = $month;
    }

    public function collection()
    {
        $group = Group::find($this->groupId);
        $students = User::role('student')->where('group_id', $group->id)->get();
        $attendances = Attendance::where('group_id', $this->groupId)
            ->whereYear('created_at', $this->year)
            ->whereMonth('created_at', $this->month)
            ->get();

        $data = [];

        foreach ($students as $student) {
            $data[$student->name] = [];

            for ($i = 1; $i <= 31; $i++) {
                $data[$student->name][str_pad($i, 2, '0', STR_PAD_LEFT)] = ''; // Initialize all days as empty
            }
        }

        foreach ($attendances as $attendance) {
            $day = $attendance->created_at->format('d');
            $data[$attendance->user->name][$day] = $attendance->status;
        }

        // Convert array data to collection format expected by Laravel Excel
        $collection = collect();

        foreach ($data as $studentName => $attendanceDays) {
            $collection->push(array_merge(['Student Name' => $studentName], $attendanceDays));
        }

        return $collection;
    }

    public function map($row): array
    {
        // Array conversion for each row, because $row is now an array not a model instance
        return array_values($row);
    }

    public function headings(): array
    {
        // Generating headings based on days of the month
        $days = range(1, 31);
        $daysFormatted = array_map(function($day) {
            return str_pad($day, 2, '0', STR_PAD_LEFT);
        }, $days);

        return array_merge(['Student Name'], $daysFormatted);
    }
}
