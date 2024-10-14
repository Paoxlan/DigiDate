<?php

use App\Enums\Gender;

$isUser = auth()->user()->isRole('user');
?>

<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}"
                 class="col-span-6 sm:col-span-4 flex justify-center gap-x-4 items-center">
                <!-- Profile Photo File Input -->
                <section>
                    <input type="file" id="photo" class="hidden"
                           wire:model.live="photo"
                           x-ref="photo"
                           x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            "/>

                    <!-- Current Profile Photo -->

                    <div class="mt-2" x-show="! photoPreview">
                        <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->firstname }}"
                             class="rounded-full w-28 h-28 object-cover">
                    </div>

                    <!-- New Profile Photo Preview -->
                    <div class="mt-2" x-show="photoPreview" style="display: none;">
                        <span class="block rounded-full w-28 h-28 bg-cover bg-no-repeat bg-center"
                              x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                        </span>
                    </div>
                </section>

                <section>
                    <x-secondary-button class="mt-2 me-2" type="button" x-on:click.prevent="$refs.photo.click()">
                        {{ __('Select A New Photo') }}
                    </x-secondary-button>

                    @if ($this->user->profile_photo_path)
                        <x-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                            {{ __('Remove Photo') }}
                        </x-secondary-button>
                    @endif
                    <x-input-error for="photo" class="mt-2"/>
                </section>

            </div>
        @endif

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4 flex gap-x-2">
            <div>
                <x-label for="name" value="{{ __('Voornaam') }}"/>
                <x-input id="name" type="text" class="mt-1 block w-full !text-gray-400" wire:model="state.firstname"
                         disabled/>
            </div>
            <div>
                <x-label for="name" value="{{ __('Tussenvoegsel') }}"/>
                <x-input id="name" type="text" class="mt-1 block w-full !text-gray-400" wire:model="state.middlename"
                         disabled/>
            </div>
            <div>
                <x-label for="name" value="{{ __('Achternaam') }}"/>
                <x-input id="name" type="text" class="mt-1 block w-full !text-gray-400" wire:model="state.lastname"
                         disabled/>
            </div>
        </div>

        @if($isUser)
            <!-- Bio -->
            <div class="col-span-6 sm:col-span-4">
                <x-label value="Bio"/>
                <x-textarea class="mt-1 block w-full" max="100" wire:model="state.bio" :text="$this->state['bio']"/>
                <x-input-error for="bio"/>
            </div>

            <!-- Residence -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="residence" value="{{ __('Woonplaats') }}"/>
                <x-input id="residence" class="mt-1 block w-full" wire:model="state.residence"
                         placeholder="{{ __('Residence') }}"/>
                <x-input-error for="residence"/>
            </div>

            <!-- Study -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="study" value="{{ __('Studie') }}"/>
                <x-input id="study" class="mt-1 block w-full" wire:model="state.study" placeholder="{{ __('Study') }}"/>
                <x-input-error for="study"/>
            </div>

            <!-- Gender -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="gender" value="{{ __('Geslacht') }}"/>
                <x-select class="mt-1 block w-full" wire:model="state.gender">
                    @foreach(Gender::cases() as $gender)
                        <option value="{{ $gender->name }}" {{ $this->user->profile->gender === $gender ? 'selected' : '' }}>
                            {{ $gender->getName() }}
                        </option>
                    @endforeach
                </x-select>
            </div>

            <!-- Birthdate -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="birthdate" value="{{ __('Geboortedatum') }}"/>
                <x-input id="birthdate" type="text" class="mt-1 block w-full !text-gray-400"
                         value="{{ $this->user->profile->birthdate }}" disabled/>
            </div>

        @endif

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Email') }}"/>
            <x-input id="email" type="email" class="mt-1 block w-full" wire:model="state.email" required
                     autocomplete="username"/>
            <x-input-error for="email" class="mt-2"/>

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <p class="text-sm mt-2 dark:text-white">
                    {{ __('Your email address is unverified.') }}

                    <button type="button"
                            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                            wire:click.prevent="sendEmailVerification">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            @endif
        </div>

        @if($isUser)
            <!-- Tags -->
            <div class="col-span-6 sm:col-span-4">
                <x-label value="{{ __('Tags') }}"/>
                <x-select class="mt-1 block w-full">
                    <option selected hidden disabled>Select a tag</option>
                    @foreach($this->tags as $tag)
                        @unless($this->hasTag($tag))
                            <option value="{{ $tag->id }}" wire:click="addTag({{ $tag->id }})">{{ $tag->name }}</option>
                        @endunless
                    @endforeach
                </x-select>


                <p class="font-medium text-sm text-gray-100 mt-1">Current tags:</p>
                <div class="flex flex-wrap gap-1 text-gray-100">
                    @forelse($this->user->getTags() as $tag)
                        <div class="bg-gray-500 rounded-lg px-2 ml-1">
                            {{ $tag }}
                            <i class="ml-0.5 fa fa-xmark cursor-pointer" wire:click="removeTag({{ $tag->id }})"></i>
                        </div>
                    @empty
                        None
                    @endforelse
                </div>
            </div>
        @endif
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
