<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div>
            @livewire('two-factor-form')
        </div>
    </x-authentication-card>
</x-guest-layout>
