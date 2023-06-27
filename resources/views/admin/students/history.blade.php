@extends('layouts.admin')
@section('content')
    <?php $i=1 ?>
    <div class="p-4 m-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <center>
            <img style="margin:19px" src="{{asset('storage/'.$student->photo)}}"  width="150px" alt="null">
        </center>
        <center>
            <h1 style="margin:19px ">{{$student->name}}</h1>
        </center>
        {{--        @dd($dept)--}}
{{--        @role('admin')--}}
        <table class="table">
            <thead>
            <tr>
                <th>month</th>
                <th>registrar</th>
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
                    <th>{{ $i .'-oy' }}</th>
                    <td>{{$student->manager}}</td>
                    <td>@if($student->little==null)
                            {{'toliq tolangan'}}
                        @else
                            {{$student->little}}
                        @endif</td>
                    <td>{{$student->created_at}}</td>
                    <td>{{$student->end_day}}</td>
                    @role('admin')
                    <td rowspan="auto">{{$student->sum}}</td>
                    @endrole
                </tr>
                @if($student->monthly_payment === 0)
                 {{$i+=1}}
                @endif
            @endforeach
            </tbody>
        </table>
{{--        @endrole--}}
    </div>
@endsection