@extends('template.master')

@section('content')
    <div class="p-4 m-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl">
            <h1 class="text-center">Create New Teacher</h1>

            <form action="{{ route('teacher.store') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="mt-2">
                    <label for="name" class="text-dark">Name</label>
                    <input id="name" name="name" value="{{ old('name') }}" type="text" class="form-control">
                    @error('name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-2">
                    <label for="phone" class="text-dark">Phone</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text">+99 8</span>
                        <input type="tel" id="phone" name="phone" pattern="[0-9]{9}" maxlength="9" class="form-control"
                               placeholder="912345678" value="{{ old('phone') }}"
                        />
                    </div>
                    @error('phone')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-2">
                    <label for="date_born" class="text-dark">Date born</label>
                    <input id="date_born" name="date_born" value="{{ old('date_born') }}" type="date"
                           class="form-control">
                    @error('date_born')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-2">
                    <label for="location" class="text-dark">Location</label>
                    <input id="location" name="location" value="{{ old('location') }}" type="text" class="form-control">
                    @error('location')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-2">
                    <label for="passport" class="text-dark">Passport</label>
                    <input id="passport" name="passport" value="{{ old('passport') }}" type="text" class="form-control">
                    @error('passport')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-2">
                    <label for="room" class="text-dark">Room</label>
                    <select name="room_id" id="room" class="form-control">
                        @foreach($rooms as $room)
                            @if($room->roomTeacher($room->id))
                                <option value="{{ $room->id }}">{{ $room->room }}</option>
                            @endif
                        @endforeach
                    </select>
                    @error('room_id')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-2">
                    <label for="percent" class="text-dark">Percent</label>
                    <input id="percent" name="percent" value="{{ old('percent') }}" type="number" class="form-control">
                    @error('percent')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-2">
                    <label for="photo" class="text-dark">Photo</label>
                    <input id="photo" name="photo" type="file" class="form-control">
                    @error('photo')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-2">
                    <button class="btn btn-warning m-4">Submit</button>
                </div>
            </form>
        </div>
    </div>

@endsection
