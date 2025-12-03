@extends('template.master')
@section('content')

    <div class="p-4 m-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl">
            <h1 class="text-center">Create Student</h1>

            <form action="{{ route('student.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label text-dark">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" class="form-control">
                    @error('name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>


                <div class="mb-3">
                    <label for="phone" class="form-label text-dark">Phone</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text">+998</span>
                        <input type="tel" id="phone" name="phone" maxlength="9" placeholder="912345678"
                               value="{{ old('phone') }}" class="form-control">
                    </div>
                    @error('phone')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>


                <div class="mb-3">
                    <label for="parents_tel" class="form-label text-dark">Parents Phone</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text">+998</span>
                        <input type="tel" id="parents_tel" name="parents_tel" maxlength="9" placeholder="912345678"
                               value="{{ old('parents_tel') }}" class="form-control">
                    </div>
                    @error('parents_tel')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="group_id" class="form-label text-dark">Group</label>
                    <select id="group_id" name="group_id" class="form-control">
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}"
                                    data-payment="{{ $group->monthly_payment }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>
                                {{ optional($group->room)->room ?? 'room '.  0 }} -> {{ $group->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('group_id')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{--                <div class="mb-3">--}}
                {{--                    <label> Gummax </label>--}}
                {{--                    <input class="form-checkbox" name="status" type="checkbox">--}}

                {{--                </div>--}}

                <div class="mb-3">
                    <label for="should_pay" class="form-label text-dark">Payment</label>
                    <input id="should_pay" name="should_pay" type="text"
                           value="{{ number_format(old('should_pay', 500000), 0, ' ', ' ') }}"
                           class="form-control">
                    @error('should_pay')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label text-dark">Description "not necessary"</label>
                    <textarea id="description" name="description"
                              class="form-control">{{ old('description') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="passport" class="form-label text-dark">Passport "not necessary"</label>
                    <input id="passport" name="passport" type="text" value="{{ old('passport') }}" class="form-control">
                    @error('passport')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="location" class="form-label text-dark">Location "not necessary"</label>
                    <input id="location" name="location" type="text" value="{{ old('location') }}" class="form-control">
                    @error('location')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="parents_name" class="form-label text-dark">Parents Name "not necessary"</label>
                    <input id="parents_name" name="parents_name" type="text" value="{{ old('parents_name') }}"
                           class="form-control">
                    @error('parents_name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="photo" class="form-label text-dark">Photo "not necessary"</label>
                    <input id="photo" name="photo" type="file" class="form-control">
                    @error('photo')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-warning m-4">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('group_id').addEventListener('change', function () {
            var selectedOption = this.options[this.selectedIndex];
            var payment = selectedOption.getAttribute('data-payment');
            document.getElementById('should_pay').value = payment;
        });

        // Trigger the change event on page load to fill in the should_pay if a group is already selected
        document.getElementById('group_id').dispatchEvent(new Event('change'));

        const shouldPayInput = document.getElementById('should_pay');


        // Function to format number with spaces
        function formatNumberWithSpaces(value) {
            return value.replace(/\D/g, '')  // Remove non-digits
                .replace(/\B(?=(\d{3})+(?!\d))/g, ' ');  // Add spaces
        }

        // Apply formatting when the user types
        shouldPayInput.addEventListener('input', function () {
            const rawValue = this.value.replace(/\s/g, ''); // Remove spaces for processing
            this.value = formatNumberWithSpaces(rawValue);  // Add spaces back
        });

        // Remove spaces before form submission
        shouldPayInput.form.addEventListener('submit', function () {
            shouldPayInput.value = shouldPayInput.value.replace(/\s/g, '');
        });
    </script>

@endsection
