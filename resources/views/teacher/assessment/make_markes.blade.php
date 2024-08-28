@extends('template.master')
@section('content')

    <div class="card">
        <form action="{{ route('assessment.update', $id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <label for="lesson" class="mr-2"></label>
                <input type="text" name="lesson" id="lesson" class="form-control w-50 m-3" placeholder="Test Name" required>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                    <tr>
                        <th>T/R</th>
                        <th>Name</th>
                        <th>Mark</th>
                        <th>Reason</th>
                        <th>Recommendation</th>
                    </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    @foreach($students as $index => $student)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $student->name }}</td>
                            <td>
                                <input type="number" class="form-control" style="width: 70px;" name="end_mark[]" required>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="reason[]" required>
                            </td>
                            <td>
                                <select class="form-select form-control" name="recommended[]" required>
                                    @foreach($groups as $group)
                                        <option value="{{ $group->name }}">{{ $group->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <input type="hidden" name="student[]" value="{{ $student->id }}">
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer mt-4">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>

@endsection

{{--    <div class="p-4 m-4 sm:p-8 bg-white shadow sm:rounded-lg ">--}}
{{--        <form action="{{route('assessment.update',$id)}}" method='post'>--}}
{{--            @csrf--}}
{{--            @method('PUT')--}}
{{--            <table class="table">--}}
{{--                <tr>--}}
{{--                    <td>no</td>--}}
{{--                    <td>name</td>--}}
{{--                    <td class="float">status</td>--}}
{{--                </tr>--}}
{{--                @csrf--}}
{{--                @foreach($students as $student)--}}
{{--                    <tr>--}}
{{--                        <th>{{$loop->index+1}}</th>--}}
{{--                        <th><b>{{$student->student->name}}</b></th>--}}
{{--                        <th>--}}
{{--                            <input type="text" class="float input-group-merge" style="height: 30px;width: 50px"--}}
{{--                                   name="end_mark[{{$student->user_id}}]">--}}
{{--                        </th>--}}
{{--                    </tr>--}}
{{--                @endforeach--}}
{{--            </table>--}}
{{--            <button type="submit" class="btn btn-primary" >topshirish</button>--}}
{{--        </form>--}}
{{--    </div>--}}
{{--@endsection--}}
