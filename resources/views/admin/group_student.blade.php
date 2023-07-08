@extends('layouts.admin')
@section('content')
    @foreach($groups as $group)
        <div class="d-flex flex-column">
            <div class="container">
                <div class="p-4 m-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg" style="width: 450px;">
                    <table class="table border-top">
                        <tr class="box-header display-block">
                            <th>
                                <h1 class="box-title display-block float-end"><span class='pull-right text-muted fs16'>{{$group->days}} " {{$group->start_day}} to {{$group->end_day}} "</span></h1>
                                <h1 class="box-title display-block "><span class='tex text-muted fs16'> </span></h1>
                                <h1>
                                    <span class="pull-left fs16"> {{$group->name}} </span>
                                </h1>
                            </th>
                        </tr>
                        <tr>
                            <th class="text-center">{{$group->teacher->name}}</th>
                        </tr>
                    </table>
                    @php
                        $students = DB::select('SELECT * FROM users WHERE group_id = ?', [$group->id]);
                    @endphp
                    @foreach($students as $student)
                        <ol>
                            <li>{{$loop->index+1}}) {{$student->name}}</li>
                        </ol>
                    @endforeach
                </div>
            </div>
        </div>

    @endforeach

@endsection