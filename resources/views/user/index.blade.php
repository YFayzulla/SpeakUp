@extends('template.master')
@section('content')

    <input type="hidden" value="{{$sum=0}}">
    <div class="float-left col-lg-12">
        <div class="p-4 m-4 sm:p-8 bg-white shadow sm:rounded-lg ">


{{--            @dd($date[1])--}}


{{--            <a class="btn btn-danger float-right m-2" href="{{ URL::to('/dept/pdf') }}?date={{ urlencode($date) }}">Report</a>--}}

            <form action="{{ URL::to('/dept/pdf') }}">

                <input type="hidden" name="startDate" value="{{$start_date}}">
                <input type="hidden" name="endDate" value="{{$end_date}}">

                <button class="btn btn-danger float-right m-2" > Report </button>

            </form>

            <div class="container" style="display: flex; justify-content: space-between;">
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <tr>
                            <th>no</th>
                            <th>name</th>
                            <th>paid</th>
                            <th>group</th>
                            <th>type</th>
                            <th>date</th>
                        </tr>
                        @foreach($users as $student)
                            <tr>
                                <th>{{$loop->index+1}}</th>
                                <th>{{$student->name}}</th>
                                <th>{{$student->payment}}</th>
                                <th>{{$student->group}}</th>
                                <th>{{$student->type_of_money}}</th>
                                <th>{{$student->date}}</th>
                                @php
                                    $sum += $student->payment
                                @endphp
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            <p>Sum in date: {{$sum}} </p>
        </div>
    </div>
@endsection

