`@extends('template.master')
@section('content')

    <div class="card">
        <form action="{{ route('attendance.submit', $id) }}" method="post">
            @csrf
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <label for="lesson" class="mr-2 align-self-center"></label>
                <input type="text" name="lesson" id="lesson" class="form-control w-25" placeholder="Lesson">
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    @foreach($users as $index => $student)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><b>{{ $student->name }}</b></td>
                            <td class="text-center">
                                <input type="checkbox" name="status[{{ $student->id }}]" value="on">
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="modal-footer mt-4">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>

        </form>

    </div>


    @include('.template.attendance')

@endsection
`