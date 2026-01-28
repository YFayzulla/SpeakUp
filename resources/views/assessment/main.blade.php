@extends('template.master')
@section('content')
    {{--    <div class="p-4 m-4 sm:p-8 bg-white shadow sm:rounded-lg "> --}}
    {{--        <div class="row"> --}}
    {{--            <div class="card"> --}}
    {{--                <h5 class="card-header">Active Students</h5> --}}
    {{--                <div class="table-responsive text-nowrap"> --}}
    {{--                    <table class="table"> --}}
    {{--                        <thead> --}}
    {{--                        <tr> --}}
    {{--                            <td>Name</td> --}}
    {{--                            <td>Group</td> --}}
    {{--                            <td>Mark</td> --}}
    {{--                        </tr> --}}
    {{--                        </thead> --}}
    {{--                        <tbody class="table-border-bottom-0"> --}}
    {{--                        @foreach ($topStudents as $topStudent) --}}
    {{--                            <tr> --}}
    {{--                                <td>{{ $topStudent->student->name }}</td> --}}
    {{--                                <td>{{ $topStudent->group }}</td> --}}
    {{--                                <td>{{ $topStudent->get_mark }}</td> --}}
    {{--                            </tr> --}}
    {{--                        @endforeach --}}
    {{--                        </tbody> --}}
    {{--                    </table> --}}
    {{--                </div> --}}
    {{--            </div> --}}
    {{--        </div> --}}

    {{--    </div> --}}


    <!-- Second Table -->

    <div class="p-4 m-4 sm:p-8 bg-white shadow sm:rounded-lg ">
        <div class="row">
            <div class="card">
                <h5 class="card-header">Test List</h5>
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Test Name</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">

                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->created_at->format('d-m-Y') }}</td>
                                    <td>
                                        <a href="{{ route('test.show', $item->id) }}" class="btn btn-sm btn-info"
                                            title="View Results">
                                            <i class="bx bx-show-alt"></i>
                                        </a>
                                        <form action="{{ route('test.destroy.all', $item->id) }}" method="POST"
                                            style="display:inline;"
                                            onsubmit="return confirm('Are you sure you want to delete this entire test and all its results? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete Test">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="card-footer">
                        {{ $data->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
