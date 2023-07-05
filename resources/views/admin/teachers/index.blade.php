@extends('layouts.admin')
@section('content')
    <div class="p-4 m-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg ">

        @role('admin')

        <h1 class="text-center">Manager</h1>

        <a href="{{route('dashboard.create')}}" type="button" class="btn-outline-success btn m-2"> <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="currentColor"
                                                                                                        class="bi bi-plus-lg" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                      d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z"/>
            </svg>  </a>

        <table class="table">
            <thead>
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
            @foreach($managers as $manager)
                <tbody id="myTable" class="table-group-divider">
                <tr>
                    <th>{{$loop->index+1}}</th>
                    <th>{{$manager->name}}</th>
                    <th>{{$manager->tel}}</th>
                    <th>{{$manager->email}}</th>
                    <th>{{$manager->desc}}</th>
                    <th><img src="{{asset('storage/'.$manager->image)}}" alt="" width="70px"></th>
                    <th class="d-flex">
                        <a href="{{route('dashboard.edit',$manager->id)}}" class="btn-outline-warning btn m-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                 class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                            </svg>
                        </a>
                        <form action="{{route('dashboard.destroy',$manager   ->id)}}" method="post"
                              onsubmit="return confirm('are you sure for deleting ');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="" class="btn-outline-danger btn m-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                     class="bi bi-trash-fill" viewBox="0 0 16 16">
                                    <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                                </svg>
                            </button>
                        </form>
                    </th>
                </tr>
                </tbody>
            @endforeach
        </table>
        @endrole

        <br>
        <h1 class="text-center">teachers</h1>
        <a href="{{route('extra.create')}}" type="button" class="btn-outline-success btn m-2"> <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="currentColor"
                                                                                                    class="bi bi-plus-lg" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                      d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z"/>
            </svg></a>
        <table class="table  ">
            <thead class="">
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
            @foreach($teachers as $teacher)
                <tbody id="myTable">
                <tr>
                    <th>{{$teacher->index+1}}</th>
                    <th>{{$teacher->name}}</th>
                    <th>{{$teacher->tel}}</th>
                    <th>{{$teacher->email}}</th>
                    <th>{{$teacher->desc}}</th>
                    <th><img src="{{asset('storage/'.$teacher->image)}}" alt="" width="70px"></th>
                    <th >
                        <div class="d-flex">
                        <a href="{{route('dashboard.edit',$teacher->id)}}" class="btn-outline-warning btn m-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                 class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                            </svg>
                        </a>
                        <form action="{{route('dashboard.destroy',$teacher->id)}}" method="post"
                              onsubmit="return confirm('are you sure for deleting ');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="" class="btn-outline-danger btn m-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                     class="bi bi-trash-fill" viewBox="0 0 16 16">
                                    <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                                </svg>
                            </button>
                        </form>
                        </div>
                    </th>
                </tr>
                </tbody>
            @endforeach
            {{--                {{$users->links()}}--}}
        </table>
    </div>
    <script>
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '{{session('success')}}',
            showConfirmButton: false,
            timer: 1500
        })
        @endif


    </script>

@endsection