@extends('template.master')

@section('content')

    <div class="card">
        <form action="{{ route('attendance.submit', $id) }}" method="post">
            @csrf
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <label for="lesson" class="mr-2 align-self-center"></label>
                <input type="text" name="lesson" id="lesson" class="form-control w-25" placeholder="Lesson">
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    @forelse($users as $index => $student)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><b>{{ $student->name }}</b></td>
                            <td class="text-center">
                                <input type="checkbox" name="status[{{ $student->id }}]" value="on">
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No students found in this group.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="modal-footer mt-4">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>

        </form>

    </div>


    <div class="card mt-3">
        <div class="p-4 mt-4 d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Attendance for {{ \Carbon\Carbon::createFromDate($year, $month)->format('F Y') }}</h4>

            <div class="d-flex">
                @role('admin')

                <div class="d-flex justify-content-center me-3">
                    <form method="GET" action="{{ route('group.attendance', $group->id) }}" class="d-flex">
                        <select id="month" name="date" class="form-select me-1">
                            <option value="">Select Month</option>
                            @php
                                $currentYear = date('Y');
                                for ($monthOption = 1; $monthOption <= 12; $monthOption++) {
                                    $monthNum = str_pad($monthOption, 2, '0', STR_PAD_LEFT);
                                    $monthYear = $currentYear . '-' . $monthNum;
                                    $monthName = date('F', mktime(0, 0, 0, $monthOption, 1));
                                    $selected = ($year . '-' . $monthNum == $monthYear) ? 'selected' : '';
                                    echo "<option value=\"$monthYear\" $selected>$monthName $currentYear</option>";
                                }
                            @endphp
                        </select>
                        <button type="submit" class="btn btn-primary">Show</button>
                    </form>
                </div>

                <div class="d-flex align-items-center">
                    <form method="GET"
                          action="{{ route('export.attendances', ['id' => $group->id, 'date' => $year . '-' . $month]) }}">
                        <button type="submit" class="btn btn-danger">Export to Excel</button>
                    </form>
                </div>
                @endrole
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered text-center">
                @php
                    use Carbon\Carbon;

                    // Get the current year, month, day, and number of days in the month
                    $currentMonthDays = Carbon::createFromDate($year, $month)->daysInMonth;
                @endphp

                <thead>
                <tr>
                    <th>Name</th>
                    @for ($i = 1; $i <= $currentMonthDays; $i++)
                        @php
                            // Create a date object for each day of the current month
                            $currentDate = Carbon::createFromDate($year, $month, $i);
                            $isWeekend = $currentDate->isWeekend();
                            $isToday = ($i == Carbon::now()->day && $month == Carbon::now()->month && $year == Carbon::now()->year);
                        @endphp
                                <!-- Add the class for weekends and highlight today in green -->
                        <th class="{{ $isToday ? 'bg-success text-white' : ($isWeekend ? 'bg-danger text-white' : '') }}">
                            {{ $i }}
                        </th>
                    @endfor
                </tr>
                </thead>

                <tbody>

                @forelse ($data as $userName => $days)
                    <tr>
                        <td>{{ $userName }}</td>
                        @for ($i = 1; $i <= $currentMonthDays; $i++)
                            @php
                                // Pad the day with leading zeros if necessary
                                $day = str_pad($i, 2, '0', STR_PAD_LEFT);
                                // Get the status for the current day
                                $status = $days[$day] ?? '1';
                                $isDanger = $status === '0' || $status === 0;
                                $isPresent = $status === '1' || $status === 1;

                                // Create a date object for the current year, month, and day
                                $currentDate = Carbon::createFromDate($year, $month, $i);
                                $isWeekend = $currentDate->isWeekend();
                            @endphp
                            <td class="{{ $isDanger ? 'bg-danger text-white' : ($isWeekend ? 'bg-danger' : '') }}">
                                @if ($isPresent)
                                    <span class="text-danger font-weight-bold">X</span>
                                @else
                                    {{ $status }}
                                @endif
                            </td>
                        @endfor
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $currentMonthDays + 1 }}" class="text-center">No attendance data available for this month.</td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>
    </div>

    <div class="card mt-4">
        <div class="table-responsive text-nowrap">
            <table class="table">
                <tr>
                    <td>id</td>
                    <td>name</td>
                    <td>teacher</td>
                    <td>lesson</td>
                    <td>date</td>
                    <td>delete</td>
                </tr>
                @forelse($students as $attendance)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $attendance->user->name }}</td>
                        <td>{{ $attendance->teacher->name }}</td>
                        <td>{{ $attendance->lesson->name }}</td>
                        <td>{{ $attendance->created_at }}</td>
                        <td>
                            <form action="{{route('attendance.delete', $attendance->id)}}" method="POST">
                                @csrf
                                @method("DELETE")
                                <button class="btn btn-danger"><i class="bx bx-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No recent attendance records found.</td>
                    </tr>
                @endforelse
            </table>
            <div class="card-body">
                {{ $students->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

@endsection