@extends('layouts.admin')
@section('content')
    <div class="p-4 m-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <center>
            <img src="{{asset('storage/'.$student->photo)}}" alt="null">
        </center>
        <center>
            <h1>{{$student->name}}</h1>
        </center>
        <table class="table">
            <thead>
            <tr>
                <th>manager</th>
                <th>monthly_payment</th>
                <th>-s sum +s</th>
                <th>paid day</th>
                <th>end day</th>
            </tr>
            </thead>
            <tbody>
            @foreach($dept as $student)
            <tr>
                <td>{{$student->manager}}</td>
                <td>{{$student->monthly_payment}}</td>
                <td>@if($student->little==null) {{'toliq tolangan'}} @else{{$student->little}} @endif</td>
                <td>{{$student->created_at}}</td>
                <td>{{$student->end_day}}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
