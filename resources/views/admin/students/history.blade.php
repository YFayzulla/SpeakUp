@extends('layouts.admin')
@section('content')
    <div class="p-4 m-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <center>
            <img src="{{asset('storage/'.$student->photo)}}" alt="null">
        </center>
        <center>
            <h1>{{$student->name}}</h1>
        </center>
        {{--        @dd($dept)--}}
{{--        @role('admin')--}}
        <table class="table">
            <thead>
            <tr>
                <th>month</th>
                <th>manager</th>
                <th>monthly_payment</th>
                <th>-s sum +s</th>
                <th>paid day</th>
                <th>end day</th>
                @role('admin')
                <th>summa</th>
                @endrole
            </tr>
            </thead>
            <tbody>
            @foreach($dept as $student)
                <tr>
                    <th>@if($student->monthly_payment !== 400000)
                            {{ $loop->index+1 .'oy' }}
                        @else {{'s'}} @endif</th>
                    <td>{{$student->manager}}</td>
                    <td>{{$student->monthly_payment}}</td>
                    <td>@if($student->little==null)
                            {{'toliq tolangan'}}
                        @else
                            {{$student->little}}
                        @endif</td>
                    <td>{{$student->created_at}}</td>
                    <td>{{$student->end_day}}</td>
                    @role('admin')
                    <td>{{$student->sum}}</td>
                    @endrole
                </tr>
            @endforeach
            </tbody>
        </table>
{{--        @endrole--}}
    </div>
@endsection
