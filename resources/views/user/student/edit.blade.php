@extends('template.master')
@section('content')

    <div class="p-4 m-4 sm:p-8 bg-white shadow sm:rounded-lg ">

        <div class="max-w-xl">
            <h1 class="text-center">Edit Student Data</h1>

            <form action="{{ route('student.update', $student->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <label for="name" class="text-dark">Name</label>
                <input id="name" name="name" value="{{ old('name', $student->name) }}" type="text" class="form-control">
                @error('name')
                <div class="alert alert-danger" role="alert">{{ $message }}</div>
                @enderror

                <label for="phone" class="text-dark">Phone</label>
                <div class="input-group input-group-merge">
{{--                    <span class="input-group-text">+99 8</span>--}}
                    <input type="tel" id="phone" name="phone" pattern="[0-9]{9}" maxlength="9" class="form-control" placeholder="912345678" value="{{ old('phone', $student->phone) }}"
                    />
                </div>
                @error('phone')
                <div class="alert alert-danger" role="alert">{{ $message }}</div>
                @enderror

                <label for="passport" class="text-dark">Passport</label>
                <input id="passport" name="passport" value="{{ old('passport', $student->passport) }}" type="text" class="form-control">

                <label for="parents_name" class="text-dark">Parents Name</label>
                <input id="parents_name" name="parents_name" value="{{ old('parents_name', $student->parents_name) }}" type="text" class="form-control">
                @error('parents_name')
                <div class="alert alert-danger" role="alert">{{ $message }}</div>
                @enderror

                <label for="parents_tel" class="text-dark">Parents Tel</label>
                <div class="input-group input-group-merge">
                    <span class="input-group-text">+99 8</span>
                    <input type="tel" id="parents_tel" name="parents_tel" pattern="[0-9]{9}" maxlength="9" class="form-control" placeholder="912345678" value="{{ old('parents_tel', $student->parents_tel) }}"
                    />
                </div>
                @error('phone')
                <div class="alert alert-danger" role="alert">{{ $message }}</div>
                @enderror

                <label for="location" class="text-dark">Location</label>
                <input id="location" name="location" value="{{ old('location', $student->location) }}" type="text" class="form-control">
                @error('location')
                <div class="alert alert-danger" role="alert">{{ $message }}</div>
                @enderror

                <label for="description" class="text-dark">Description</label>
                <input id="description" name="description" value="{{ old('description', $student->description) }}" type="text" class="form-control">

                <label for="should_pay" class="text-dark">Should Pay</label>
                <input id="should_pay" name="should_pay" value="{{ old('should_pay', $student->should_pay) }}" type="text" class="form-control">

                <label for="group_id" class="text-dark">Group</label>
                <select id="group_id" class="form-control" name="group_id">
                    <option value="{{ $student->group_id }}" selected>{{ $student->group->name }}</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}" data-payment="{{ $group->monthly_payment }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>
                            {{ optional($group->room)->room }} -> {{ $group->name }}
                        </option>
                    @endforeach
                </select>
                @error('group_id')
                <div class="alert alert-danger" role="alert">{{ $message }}</div>
                @enderror

                <label for="photo" class="text-dark">Photo</label>
                <input id="photo" name="photo" type="file" class="form-control">

                <button type="submit" class="btn btn-warning m-4">Submit</button>
            </form>
        </div>
    </div>

@endsection
