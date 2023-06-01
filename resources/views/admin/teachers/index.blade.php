@extends('layouts.admin')
@section('content')

    {{--@dd($users)--}}

    <div class="p-4 m-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg ">
        <a href="{{route('dashboard.create')}}" type="button" class="btn-outline-success btn m-2"> new</a>
            <table class="table table-hover">
                <thead class="table-active">
                <tr>
                    <th>id</th>
                    <th>name</th>
                    <th>tel</th>
                    <th>email</th>
                    <th>desc</th>
                    <th>image</th>
                    <th class="text-md-center">action</th>
                </tr>
                </thead>
                @foreach($users as $user)
                <tr>
                    <th>{{$loop->index+1}}</th>
                    <th>{{$user->name}}</th>
                    <th>{{$user->tel}}</th>
                    <th>{{$user->email}}</th>
                    <th>{{$user->desc}}</th>
                    <th><img src="Photo/{{$user->image}}" alt="" width="70px"></th>
                    <th class="text-center">
                        <a href="#" type="button" class="btn-outline-warning btn m-2">edit</a>
                        <button href="#" type="button" class="btn-outline-danger btn m-2">del</button>
                    </th>
                </tr>
                @endforeach
            </table>
    </div>

@endsection