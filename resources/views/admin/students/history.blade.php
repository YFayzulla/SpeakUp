@extends('layouts.admin')
@section('content')
    <?php

    use Carbon\Carbon;

    ?>

{{--    @dd($student->image)--}}

    <div class="p-4 m-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <center>
            <img  src="{{ asset("storage/".$student->image) }}" style="" class="card-img-right"  width="150px" alt="asd">
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
{{--                <th>status</th>--}}
            </tr>
            </thead>
            <tbody>
            @foreach($dept as $d)
                <tr>
                    <td>{{$d->manager}}</td>
                    <th>{{$d->sum}}</th>
                    @if($d->sum == null)
                    <td></td>
                    @else
                        <td>{{$d->created_at}}</td>

                    @endif
                    @endforeach
                  {{--  <th>
                        <?php
                        if ($student->day > 0) {

                            $date = Carbon::now()->addDays($student->day);
                            echo $date;
                        } else echo '<h2 class="text-danger">' . 'qarz' . '</h2>';

                        ?>
                    </th>--}}
                </tr>

            </tbody>
            <tfoot class="footer">
            </tfoot>
        </table>
{{--        <input type="button" class="btn" value="{{round($student->day * $money->sum / 30)}} sum">--}}

        <h1 class="text-center m-5">Attendance</h1>
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
