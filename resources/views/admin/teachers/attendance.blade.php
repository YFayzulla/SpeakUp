@extends('layouts.admin')
@section('content')

    <div class="p-4 m-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <center>
            <h1>Attendance</h1><br>
            <b>{{$user->name}}</b>
        </center>

        @foreach($groups as $group)
            <h1>{{$group->name .'->'. $group->days}} </h1>
{{--            <form action="{{ route('index.attendance.store') }}" method="post">--}}
{{--                @csrf--}}
            <table class="table m-2">
                <thead>
                <tr>
                    <th>name</th>
                    <th class="float-end">status</th>
                </tr>
                </thead>
                @php

                    $students = DB::select('SELECT * FROM users WHERE group_id = ?', [$group->id]);
                @endphp
                @foreach($students as $student)
                    <tbody>
                    <tr>
                        <th>{{$student->name}}</th>
                        <th><input type="checkbox" class="float-end" name ></th>
                    </tr>
                    </tbody>
                @endforeach

            </table>

            <button class="btn-outline-primary btn float-end">submit</button>
{{--            </form>--}}
        @endforeach

    </div>

@endsection