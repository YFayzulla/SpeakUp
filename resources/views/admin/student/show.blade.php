@extends('layouts.app')
@section('content')

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <h5 class="mb-0">Student`s data</h5>
            <div class="dt-action-buttons text-end pt-3 pt-md-0">
                <div class="dt-buttons btn-group flex-wrap">
                    <div class="btn-group">
                        <a class="btn buttons-collection dropdown-toggle btn-label-primary me-2" tabindex="0"
                           aria-controls="DataTables_Table_0" type="button" id="dropdownMenuButton"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <span><i class="bx bx-export me-sm-1"></i> <span
                                        class="d-none d-sm-inline-block">Export</span></span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="{{ URL::to('/student/pdf',$student->id) }}"><i
                                            class="bx bxs-file-pdf me-1"></i> Pdf</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md">
                <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-md-8">
                            <div class="card-body">
                                <h4><b>Full Name: </b>{{$student->name}}</h4>
                                <h4><b>Location:</b> {{$student->location}}</h4>
                                <h4><b>Tel: </b>{{$student->phone}}</h4>
                                <h4><b>Parents name: </b>{{$student->parents_name}} </h4>
                                <h4><b>Parents tel: </b> {{$student->parents_tel}}</h4>
                                <h4><b>Description: </b> {{($student->description)}}</h4>
                                <h4><b>Last Test Result: </b>{{$student->mark}}</h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <img class="card-img card-img-right" src="{{asset( 'storage/'.$student->photo) }}" alt="No Photo" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-6 mt-4">
            <div class="card">
                <h5 class="card-header">Payment history</h5>
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Paid</th>
                            <th>type</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                        @foreach($student->studenthistory as $item)
                            <tr>
                                <th>{{$loop->index+1}}</th>
                                <th>{{number_format($item->payment,0,'',' ')}}</th>
                                <th>{{$item->type_of_money}}</th>
                                <th>@if($item->date ==null)
                                        {{$item->created_at.'data'}}
                                    @else
                                        {{$item->date}}
                                    @endif</th>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6 mt-4">
            <div class="card">
                <h5 class="card-header">Travel of group</h5>
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>group</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                        @foreach($student->studentinformation as $item)

                            <tr>
                                <th>{{$loop->index+1}}</th>
                                <th>{{$item->group}}</th>
                                <th>{{$item->created_at}}</th>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
