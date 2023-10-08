@extends('layouts.admin')
@section('content')

    <div class="p-4 m-4 sm:p-8 bg-white shadow sm:rounded-lg">
{{--        @dd(session('success'))--}}

        <center>
            <h1>Attendance</h1><br>
            <b>{{$user->name}}</b>
        </center>

        @foreach($groups as $group)
            <h1>{{$group->name .'->'. $group->days.'->'.$group->start_day .' to '. $group->end_day }} </h1>
            <form action="{{ route('index.attendance.store') }}" method="post">
                @csrf
            <table class="table m-2">
                <thead>
                <tr>
                    <th style="padding-left: 30px">name</th>
                    <th class="float-end ">status</th>
                </tr>
                </thead>
                @php

                    $students = DB::select('SELECT * FROM users WHERE group_id = ?', [$group->id]);
                @endphp
                @foreach($students as $student)
                    <tbody>
                    <tr>

                        <th style="padding-left: 30px">{{$student->name}}</th>
                        <input type="hidden" value="{{$group->id}}" name="group_id">
                        <th><input type="checkbox" class="float-end" style="padding-left: 20px" name="status[{{$student->id}}]" value="on" ></th>
                    </tr>
                    </tbody>
                @endforeach

            </table>

            <button class="btn-outline-primary btn float-end">submit</button>
            </form>
            <br>
        @endforeach
    </div>
    <script>
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '{{@session('success')}}',
            showConfirmButton: false,
            timer: 1500
        })
        @endif
    </script>
@endsection


