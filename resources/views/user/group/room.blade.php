@extends('template.master')
@section('content')

    <div class="row">

            <div class="col-md-6 col-xl-6 mb-3">
                <a href="{{ route('group.show', 0) }}" class="text-decoration-none">
                    <div class="card bg-primary text-white">
                        <div class="card-header">Room</div>
                        <div class="card-body">
                            <h5 class="card-title text-white"></h5>
                            <p class="card-text">SpeakUp xonalaridan biri</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-xl-6 mb-3">
                <a href="{{ route('group.index', 1) }}" class="text-decoration-none">
                    <div class="card bg-primary text-white">
                        <div class="card-header">Room</div>
                        <div class="card-body">
                            <h5 class="card-title text-white"></h5>
                            <p class="card-text">SpeakUp xonalaridan biri</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-xl-6 mb-3">
                <a href="{{ route('group.index', 2) }}" class="text-decoration-none">
                    <div class="card bg-primary text-white">
                        <div class="card-header">Room</div>
                        <div class="card-body">
                            <h5 class="card-title text-white"></h5>
                            <p class="card-text">SpeakUp xonalaridan biri</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-xl-6 mb-3">
                <a href="{{ route('group.index', 3) }}" class="text-decoration-none">
                    <div class="card bg-primary text-white">
                        <div class="card-header">Room</div>
                        <div class="card-body">
                            <h5 class="card-title text-white"></h5>
                            <p class="card-text">SpeakUp xonalaridan biri</p>
                        </div>
                    </div>
                </a>
            </div>

    </div>

@endsection
