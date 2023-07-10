@extends('layouts.admin')
@section('content')
    <?php

    use Carbon\Carbon;

    ?>
    <div class="p-4 m-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <center>
            <img style="" class="card-img-right" src="{{asset('storage/'.$student->image)}}" width="150px" alt="null">
        </center>
        <center>
            <h1 style="margin:19px ">{{$student->name}}</h1>
        </center>
        <table class="table">
            <thead>
            <tr>
                <th>registrar</th>
                <th>paid</th>
                <th>paid day</th>
                <th>status</th>
            </tr>
            </thead>
            <tbody>
            @foreach($dept as $d)
                <tr>
                    <td>{{$d->manager}}</td>
                    <th>{{$d->sum}}</th>
                    <td>{{$d->created_at}}</td>
                    @endforeach
                    <th>
                        <?php
                        if ($student->day > 0) {

                            $date = Carbon::now()->addDays($student->day);
                            echo $date;
                        } else echo '<h2 class="text-danger">' . 'qarz' . '</h2>';

                        ?>
                    </th>
                </tr>

            </tbody>

        </table>
        <h1 class="text-center m-5">Davomad</h1>
        <table class="table">
            <tr>
                <th>NO</th>
                <th>NAME</th>
{{--                <th>teacher</th>--}}
                <th>group</th>
                <th>date</th>
            </tr>
            @foreach($attendances as $attendance)
                <tr>
                    <th>{{$loop->index+1}}</th>
                    <th>{{$student->name}}</th>
                    <th>{{$attendance->group->name}}</th>
{{--                    <th>{{$attendances->student->name}}</th>--}}
{{--                    <th></th>--}}
                    <th>{{$attendance->created_at}}</th>
                </tr>
            @endforeach
        </table>


    </div>
@endsection