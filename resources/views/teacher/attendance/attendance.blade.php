@extends('template.master')
@section('content')

    <div class="card">
        <form action="{{route('attendance.submit', $id)}}" method='post'>
            @csrf
            @method('PUT')
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">

                <label for="lesson" class="mr-2 align-self-center"></label>
                <input type="text" name="lesson" id="lesson" class="form-control w-25" placeholder="Lesson">

            </div>

            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                    <tr>
                        <th>no</th>
                        <th>name</th>
                        <th>status</th>
                    </tr>
                    </thead>
                    @php($i=0)
                    @foreach($students as $student)
                        <tbody class="table-border-bottom-0">
                        <tr>
                            <td>{{$loop->index + 1}}</td>
                            <td><b>{{$student->name}}</b></td>
                            <td class="text-center">
                                <input type="checkbox" name="status[{{$student->id}}]" value="on">
                            </td>
                        </tr>
                        </tbody>
                        @php($i++)
                    @endforeach
                </table>
            </div>
            <div class="modal-footer mt-4">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
@endsection
