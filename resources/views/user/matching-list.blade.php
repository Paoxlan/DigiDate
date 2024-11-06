<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Matching lijst') }}
        </h2>
    </x-slot>

    <div class="py-8 flex flex-col items-center">
        @livewire('matching-profiles')
    </div>
</x-app-layout>
