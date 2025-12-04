@extends('template.master')
@section('content')

    <div class="row">
        @forelse($rooms as $room)
            <div class="col-md-6 col-xl-6 mb-3">
                <a href="{{ route('group.show', $room->id) }}" class="text-decoration-none">
                    <div class="card bg-info text-white">
                        <div class="card-header">Room {{ $room->room }}</div>
                        <div class="card-body">
                            <h5 class="card-title text-white"></h5>
                            <p class="card-text"></p>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <p class="text-center">No rooms found.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

@endsection