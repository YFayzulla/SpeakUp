@extends('template.master')
@section('content')

    <div class="card">
        <div class="card-header">
            <h5>List of students in the group</h5>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Group</th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                @forelse($students as $student)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $student->name }}</strong></td>
                        <td>{{ $student->phone }}</td>

                        {{-- XATOLIK BO'LGAN JOY TUZATILDI: --}}
                        {{-- ?->name ishlatildi va ?? '...' qo'shildi --}}
                        <td>
                            <span class="badge bg-label-primary">
                                {{ $student->group?->name ?? 'No group' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No students found in this group.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection