<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Matching lijst') }}
        </h2>
    </x-slot>

    <div class="py-8 flex flex-col items-center">
        <div
            class="gray-700 dark:text-gray-300 dark:bg-gray-800 lg:max-w-7xl w-full py-2 px-4 rounded-md grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($users as $user)
                @if($user->isRole('admin'))
                    @continue
                @endif

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
    </div>
</x-app-layout>
