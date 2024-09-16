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
                            for ($month = 1; $month <= 12; $month++) {
                                $monthNum = str_pad($month, 2, '0', STR_PAD_LEFT);
                                $monthYear = $currentYear . '-' . $monthNum;
                                $monthName = date('F', mktime(0, 0, 0, $month, 1));
                                $selected = (date('Y-m') == $monthYear) ? 'selected' : '';
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
            <thead>
            <tr>
                <th>Name</th>
                @for ($i = 1; $i <= 31; $i++)
                    @php
                        $day = str_pad($i, 2, '0', STR_PAD_LEFT);
                        $isToday = ($i == $today);
                    @endphp
                    <th class="{{ $isToday ? 'bg-success' : '' }}">{{ $day }}</th>
                @endfor
            </tr>
            </thead>
            <tbody>
            @foreach ($data as $userName => $days)
                <tr>
                    <td>{{ $userName }}</td>
                    @for ($i = 1; $i <= 31; $i++)
                        @php
                            $day = str_pad($i, 2, '0', STR_PAD_LEFT);
                            $status = $days[$day] ?? '1';
                            $isDanger = $status === '0' || $status === 0;
                            $isPresent = $status === '1' || $status === 1;
                        @endphp
                        <td class="{{ $isDanger ? 'bg-danger text-white' : '' }}">
                            @if ($isPresent)
                                <span class="text-danger font-weight-bold">X</span>
                            @else
                                {{ $status }}
                            @endif
                        </td>
                    @endfor
                </tr>
            @endforeach
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
            @foreach($students as $attendance)
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
            @endforeach
        </table>

        <div class="card-body">
            {{ $students->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
{{ $students->links() }}

