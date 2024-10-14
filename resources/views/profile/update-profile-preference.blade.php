<x-form-section submit="updateProfilePreference">
    <x-slot name="title">
        {{ __('Preferences') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s preferences.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Gender -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="gender" value="{{ __('Attracted gender') }}"/>
            <x-select class="mt-1 block w-full" wire:model="state.gender">
                <option value="" {{ $this->state['gender'] ? '' : 'selected' }}>
                    {{ __("Doesn't matter") }}
                </option>
                @foreach(\App\Enums\Gender::cases() as $gender)
                    <option value="{{ $gender->name }}" {{ $this->state['gender'] === $gender ? 'selected' : '' }}>
                        {{ $gender->getName() }}
                    </option>
                @endforeach
            </x-select>
        </div>

        <div class="col-span-6 sm:col-span-4 flex gap-x-2">
            <section>
                <x-label for="minimum_age" value="{{ __('Minimum age') }}"/>
                <x-input class="mt-1 block w-full" type="number" min="18" wire:model="state.minimum_age"/>
                <x-input-error for="minimum_age"/>
            </section>
            <section>
                <x-label for="maximum_age" class="text-end" value="{{ __('Maximum age') }}"/>
                <x-input class="mt-1 block w-full" type="number" min="18" wire:model="state.maximum_age"/>
                <x-input-error for="maximum_age"/>
            </section>
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-form-section>
