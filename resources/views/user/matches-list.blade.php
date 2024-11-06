<x-app-layout>
    <div class="flex text-gray-100">
        <section class="h-[calc(100vh-4.1rem)] w-1/6 bg-white dark:bg-gray-800 flex flex-col">
            @foreach($matched_users as $matchedUser)
                <a class="hover:bg-gray-700" href="{{ route('match.chat', ['user' => $matchedUser]) }}">
                    <div class="flex gap-x-4 items-center py-2 px-3">
                        <img class="rounded-full" src="{{ $matchedUser->profile_photo_url }}"
                             alt="{{ $matchedUser->firstname }}">
                        <h1 class="text-md">{{ $matchedUser->full_name }}</h1>
                    </div>
                </a>
            @endforeach
        </section>
        @if (isset($chatting_with))
            <section class="flex-grow flex flex-col h-[calc(100vh-5rem)]">
                @livewire('chat', ['chattingWith' => $chatting_with])
            </section>
        @endif
{{--        <section class="flex-grow flex flex-col justify-between">--}}
{{--            <div class="flex-grow"></div>--}}
{{--            <div class="h-8 bg-gray-100">--}}

{{--            </div>--}}
{{--        </section>--}}
    </div>

</x-app-layout>
