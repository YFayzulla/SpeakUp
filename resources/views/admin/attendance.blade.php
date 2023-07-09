@section('content')
@extends('layouts.admin')

<div class="p-4 m-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg" >
    <h1 class="text-center">Attendance</h1>
    <table class="table text-black">
        <tr>
            <th>NO</th>
            <th>name</th>
            <th>group</th>
            <th>date</th>
            <th>delete</th>
        </tr>
        @foreach($attendances as $attendance)
        <tr>
            <th>{{$loop->index+1}}</th>
            <th>{{$attendance->student->name}}</th>
            <th>{{$attendance->group->name}}</th>
            <th>{{$attendance->created_at}}</th>
            <th>
                <form action="{{route('delete_attendance',$attendance->id)}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                                       class="bi bi-trash-fill" viewBox="0 0 16 16">
                            <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                        </svg></button>
                </form>
            </th>
        </tr>
        @endforeach
    </table>

</div>


@endsection

@section('scripts')

    <script>
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '{{@session('success')}}',
            showConfirmButton: false,
            timer: 1500
        })
        @endif
    </script>
@endsection