@extends('template.master')
@section('content')

    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                <tr>
                    <th>id</th>
                    <th>Name</th>
                    <th>T/N</th>
                    <th>group</th>
                </tr>
                </thead>
                @forelse($students as $student)
                    <tbody id="myTable" class="table-group-divider">
                    <tr>
                        <th>{{$loop->index+1}}</th>
                        <th>{{$student->name}}</th>
                        <th>{{$student->phone}}</th>
                        <th>{{$student->group->name}}</th>
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