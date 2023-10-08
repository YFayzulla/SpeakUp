@section('content')
    @extends('layouts.admin')

    <div class="p-4 m-4 sm:p-8 bg-white shadow sm:rounded-lg ">
        {{--@dd($user)--}}

        <div class="max-w-xl">
            <h1 class="text-center">Edit User</h1>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form action="{{route('student.update',$user->id)}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <x-input-label for="password text-dark" :value="__('name')" class="text-dark"/>
                <x-text-input id="current_password" name="name" type="text" value="{{$user->name}}"
                              class="mt-1 block w-full bg-light text-dark text-dark"/>

                <x-input-label for="p" :value="__('Email')" class="text-dark"/>
                <x-text-input id="p" name="email" type="text" value="{{$user->email}}"
                              class="mt-1 block w-full bg-light text-dark"/>

                <x-input-label for="a" :value="__('Password')" class="text-dark"/>
                <x-text-input id="a" name="password" type="password" class="mt-1 block w-full bg-light text-dark"/>

                <x-input-label for="text" :value="__('Phone Number')" class="text-dark"/>
                <x-text-input id="text" name="tel" type="number" value="{{$user->tel}}"
                              class="mt-1 block w-full bg-light text-dark"/>

                <x-input-label for="dark" :value="__('Description')" class="text-dark"/>
                <x-text-input id="dark" name="desc" type="text" value="{{$user->desc}}"
                              class="mt-1 block w-full bg-light text-dark"/>

                <x-input-label for="dark" :value="__('group')" class="text-dark"/>
                <select name="group_id" id="" class="mt-1 block w-full bg-light text-dark">
                    @foreach($groups as $group)
                        <option value="{{$group->id}}">{{$group->name}}</option>
                    @endforeach
                </select>
                <x-input-label for="n" :value="__('Take a Photo !')" class="text-dark"/>
                <div class="d-flex">

                    <x-text-input id="n" name="image" type="file" class="mt-1 block bg-light text-dark"/>
                    <select name="status" class="select2" style="margin-left: 20px">
                        <option value="{{null}}">active</option>
                        <option value="{{0}}">passive</option>
                    </select>
                </div>


                <button class="btn btn-outline-primary m-2" type="submit">Save</button>
                <a href="{{route('student.index')}}" class="btn-outline-danger btn m-2">bekor qilish</a>


            </form>
        </div>
    </div>

@endsection
