@extends('layouts.admin')
@section('content')
    <div class="p-4 m-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <h1 class="text-center">Students</h1>
        <a href="{{route('student.create')}}" type="button" class="btn-outline-success btn m-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="currentColor"
                 class="bi bi-plus-lg" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                      d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z"/>
            </svg>
        </a>
        <table class="table">
            <thead>
            <tr>
                <th>id</th>
                <th>name</th>
                <th>email</th>
                <th>tel</th>
                <th>Parents tel</th>
                <th>End Day</th>
                <th class="text-center">actions</th>
            </tr>
            </thead>
            @foreach($students as $student)
                <tbody id="myTable" class="table-group-divider">
                <tr>
                    <th>{{$loop->index+1}}</th>
                    <th>{{$student->name}}</th>
                    <th>{{$student->email}}</th>
                    <th>{{$student->tel}}</th>
                    <th>{{$student->parents_tel}}</th>
                    <td><?php
                            $date = new DateTime();
                            $date->modify("$student->day days");
                            echo $date->format('Y-m-d');
                            ?></td>
                    <th class="d-flex">

                        <button type="button" class="btn-outline-success btn m-2" data-bs-toggle="modal"
                                data-bs-target="#exampleModal{{$student->id}}" data-bs-whatever="@mdo"
                                style="float:right">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                 class="bi bi-coin" viewBox="0 0 16 16">
                                <path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9H5.5zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518l.087.02z"/>
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11zm0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/>
                            </svg>
                        </button>
                        {{--Modal--}}
                        <div class="modal fade" id="exampleModal{{$student->id}}" tabindex="-1"
                             aria-labelledby="exampleModalLabel"
                             aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">New pay</h1>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{route('extra.update',$student->id)}}" method="post">
                                            @csrf
                                            @method('PUT')
                                            <label for="recipient-name"
                                                   class="col-form-label">{{$student->name}}</label>
                                            <div class="mb-3 d-flex">
                                                <input type="number" class="form" value="400000" name="payment"
                                                       id="recipient-name">
                                                <button type="submit" class="btn btn-outline-primary m-2">save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <a href="{{route('student.show',$student->id)}}" class="btn-outline-warning btn m-2"
                           style="float:right">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                 class="bi bi-clock-history" viewBox="0 0 16 16">
                                <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483zm.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535zm-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z"/>
                                <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0v1z"/>
                                <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z"/>
                            </svg>
                        </a>

                        <a href="{{route('student.edit',$student->id)}}" class="btn-outline-primary btn m-2"
                           style="float:right">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                 class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                            </svg>
                        </a>
                        <form action="{{route('dashboard.destroy',$student->id)}}" method="post"
                              onsubmit="return confirm('are you sure for deleting ');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-outline-danger btn m-2">
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
    </div>
    {{--    @dd(session('success'))--}}
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