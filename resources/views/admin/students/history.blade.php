@extends('layouts.admin')
@section('content')
    <?php $i = 1 ?>
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
{{--                <th>month</th>--}}
                <th>registrar</th>
                <th>paid</th>
                <th>paid day</th>
                <th>end day</th>
            </tr>
            </thead>
            <tbody>
            @foreach($dept as $d)
                <tr>
{{--                    <th>{{ $i .'-oy' }}</th>--}}
                    <td>{{$d->manager}}</td>
                    <th>{{$d->sum}}</th>
                    <td>{{$d->created_at}}</td>
                    @endforeach

                    <th colspan="2"><?php
                            $date = new DateTime();
                            if (isset($student->day)) {
                                $date->modify("$student->day days");
                                echo $date->format('Y-m-d');
                            }
                            else echo null
                            ?>

                    </th>
                </tr>

{{--                @if($d->monthly_payment === 0)--}}
{{--                    {{$i+=1}}--}}
{{--                @endif--}}
            </tbody>
        </table>
        {{--        @endrole--}}
    </div>
@endsection