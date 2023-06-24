@extends('layouts.admin')
@section('content')
    <div class="p-4 m-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
{{--        @if(isset($student->hasFile('image')))--}}
{{--        @dd($student)--}}
        <center>
            <img src="{{asset('storage/'.$student->photo)}}" alt="null">
        </center>

{{--        @endif--}}
    </div>
@endsection
