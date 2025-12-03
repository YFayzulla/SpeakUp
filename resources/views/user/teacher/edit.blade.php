@extends('template.master')
@section('content')

    <div class="p-4 m-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl">
            <h1 class="text-center">Edit teacher's data</h1>

            <form action="{{ route('teacher.update', $teacher->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mt-2">
                    <label for="name" class="text-dark">Name</label>
                    <input id="name" name="name" value="{{ $teacher->name }}" type="text" class="form-control">
                    @error('name')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-2">
                    <label for="password" class="text-dark">Password</label>
                    <input id="password" name="password" type="password" class="form-control">
                    @error('password')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-2">
                    <label for="phone" class="text-dark">Phone</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text">+99 8</span>
                        <input type="tel" id="create_phone" name="phone" pattern="[0-9]{9}" maxlength="9"
                               class="form-control" placeholder="912345678" value="{{ substr($teacher->phone, 3) }}" />
                    </div>
                    @error('phone')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-2">
                    <label for="date_born" class="text-dark">Date born</label>
                    <input id="date_born" name="date_born" value="{{ $teacher->date_born }}" type="date"
                           class="form-control">
                    @error('date_born')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-2">
                    <label for="location" class="text-dark">Location</label>
                    <input id="location" name="location" value="{{ $teacher->location }}" type="text"
                           class="form-control">
                    @error('location')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-2">
                    <label for="percent" class="text-dark">Percent</label>
                    <input id="percent" name="percent" value="{{ $teacher->percent }}" type="text"
                           class="form-control">
                    @error('percent')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-2">
                    <label for="passport" class="text-dark">Passport</label>
                    <input id="passport" name="passport" value="{{ $teacher->passport }}" type="text"
                           class="form-control">
                    @error('passport')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-2">
                    <label for="room" class="text-dark">Room</label>
                    <select name="room_id" id="room" class="form-control">
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ $teacher->room_id == $room->id ? 'selected' : '' }}>
                                {{ $room->room }}
                            </option>
                        @endforeach
                    </select>
                    @error('room_id')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-2">
                    <label for="photo" class="text-dark">Photo</label>
                    <input id="photo" name="photo" type="file" class="form-control">
                    @error('photo')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-2">
                    <button class="btn btn-warning m-4">Submit</button>
                </div>
            </form>
        </div>
    </div>

@endsection
