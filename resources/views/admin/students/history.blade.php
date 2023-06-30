@extends('layouts.admin')
@section('content')
    <?php $i = 1 ?>
    <div class="p-4 m-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <center>
            <img style="margin:19px" src="{{asset('storage/'.$student->photo)}}" width="150px" alt="null">
        </center>
        <center>
            <h1 style="margin:19px ">{{$student->name}}</h1>
        </center>
        {{--        @dd($dept)--}}
        {{--        @role('admin')--}}
        <table class="table">
            <thead>
            <tr>
{{--                <th>month</th>--}}
                <th>registrar</th>
                <th>paid</th>
                <th>paid day</th>
                <th>end day</th>
                @role('admin')
                @endrole
            </tr>
            </thead>
            <tbody>
            @foreach($dept as $d)
                <tr>
{{--                    <th>{{ $i .'-oy' }}</th>--}}
                    <td>{{$d->manager}}</td>
                    <th>{{$d->sum}}</th>
                    <td>{{$d->created_at}}</td>
                    <td><?php
                            $date = new DateTime();
                            $date->modify("$student->day days");
                            echo $date->format('Y-m-d');
                            ?></td>
                </tr>
{{--                @if($d->monthly_payment === 0)--}}
{{--                    {{$i+=1}}--}}
{{--                @endif--}}
            @endforeach
            </tbody>
        </table>
        {{--        @endrole--}}
    </div>
@endsection