@php
    $users = $this->getUsers();
@endphp

<div class="gray-700 dark:text-gray-300 dark:bg-gray-800 lg:max-w-7xl w-full py-2 px-4 rounded-md">
    <div class="w-2/5">
        <h1 class="text-lg">{{ __('Preferences') }}</h1>
        <div class="flex gap-x-2 mt-1">
            <section>
                <x-label for="minimum_age" value="{{ __('Minimum age') }}"/>
                <x-input class="mt-1 block w-full" type="number" min="18" wire:model.live="minimumAge"/>
                <x-input-error for="minimum_age"/>
            </section>
            <section>
                <x-label for="maximum_age" class="text-end" value="{{ __('Maximum age') }}"/>
                <x-input class="mt-1 block w-full" type="number" min="18" wire:model.live="maximumAge"/>
                <x-input-error for="maximum_age"/>
            </section>
        </div>
        <!-- Tags -->
        <div class="mt-4">
            <x-label value="{{ __('Tags') }}"/>
            <x-select class="mt-1 block w-full">
                <option selected hidden disabled>Select a tag</option>
                @foreach(\App\Models\Tag::all() as $tag)
                    @unless($this->hasTag($tag))
                        <option value="{{ $tag->id }}" wire:click="addTag({{ $tag->id }})">{{ $tag->name }}</option>
                    @endunless
                @endforeach
            </x-select>

            <div class="flex flex-wrap gap-1 text-gray-100 mt-1">
                @foreach($this->tags as $tag)
                    <div class="bg-gray-500 rounded-lg px-2 ml-1">
                        {{ $tag }}
                        <i class="ml-0.5 fa fa-xmark cursor-pointer" wire:click="removeTag({{ $tag->id }})"></i>
                    </div>
                @endforeach
            </div>

            <div class="mt-2">
                <x-label for="minimum_tags" value="{{ __('Minimum required tags') }}"/>
                <x-input class="mt-1 block w-full" id="minimum_tags" type="number" min="0"
                         wire:model.live="minimumTags"/>
            </div>

        </div>
        <div class="flex items-center gap-x-2 mt-4">
            <input type="checkbox" wire:model.live="usePreference"/>
            <p>{{ __('Use preferences') }}</p>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($users as $user)
            <div class="relative w-full h-0" style="padding-bottom: 125%;">
                <img src="{{$user->profile_photo_url}}" alt="{{$user->firstname}}"
                     class="absolute inset-0 w-full h-full object-cover rounded-md"/>
                <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-center py-2">
                    {{ $user->firstname }} {{ $user->middlename }} {{ $user->lastname}}
                    , {{ \Carbon\Carbon::parse($user->profile->birthdate)->age }} jaar.
                    <br>
                    {{ $user->profile->bio }}
                </div>
                <div class="absolute top-2 right-2 flex space-x-2">
                    <form action="{{route('matching.dislike', $user->id)}}" method="POST">
                        @csrf
                        <button class="bg-red-500 hover:bg-red-600 text-white rounded-full p-2" type="submit">
                            👎
                        </button>
                    </form>
                    <form action="{{ route('matching.like', $user->id) }}" method="POST">
                        @csrf
                        <button class="bg-green-500 hover:bg-green-600 text-white rounded-full p-2" type="submit">

                            👍
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-4">
        {{ $users->links()  }}
    </div>

</div>

