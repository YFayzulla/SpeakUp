    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 text-dark">
                {{ __('Extra Information') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                {{ __("Update your account's profile information ") }}
            </p>


            <br>
            @if ($errors->any())
                <div class="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </header>

        <form action="{{route('profile.money')}}" method="post">
            @csrf
            @method('PUT')
            <div>
                <x-input-label for="sum" :value="__('Monthly Payment')"/>
                <x-text-input name="sum" id="sum" type="number" class="mt-1 m-2 block w-full bg-white text-dark" required autofocus
                              autocomplete="tel"/>
            </div>
            <div class="flex items-center gap-4">
                <button class="btn btn-primary m-t-3">{{ __('Save') }}</button>
            </div>
        </form>

        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.save') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <x-input-label for="name1" :value="__('Phone number')"/>
                <x-text-input id="name1" name="tel" type="text" class="mt-1 m-2 block w-full bg-white text-dark"
                              :value="old('tel', $user->tel)" required autofocus autocomplete="tel"/>
            </div>
            <div>
                <x-input-label for="name2" :value="__('Information not necessary')"/>
                <x-text-input id="name2" name="desc" type="text" class="mt-1 m-2 block w-full bg-white text-dark"
                              :value="old('desc', $user->desc)"/>
            </div>
            <div>
                <x-input-label for="name3" :value="__('image')"/>
                <x-text-input id="name3" name="image" type="file" class="mt-1 m-2 block w-full bg-white text-dark"/>
            </div>

            <div class="flex items-center gap-4">
                <button class="btn btn-primary">{{ __('Save') }}</button>
            </div>
        </form>

    </section>
