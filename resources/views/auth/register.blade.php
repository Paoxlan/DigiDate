<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo/>
        </x-slot>

        <x-validation-errors class="mb-4"/>

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf
            <section class="flex justify-between w-full gap-x-2">
                <div>
                    <x-label for="firstname" value="{{ __('Voornaam*') }}"/>
                    <x-input id="firstname" class="block mt-1 w-full" type="text" name="firstname"
                             :value="old('firstname')" required autofocus autocomplete="firstname"/>
                </div>
                <div>
                    <x-label for="middlename" value="{{ __('Tussenvoegsel') }}"/>
                    <x-input id="middlename" class="block mt-1 w-full" type="text" name="middlename"
                             :value="old('middlename')" autocomplete="middlename"/>
                </div>
                <div>
                    <x-label for="lastname" value="{{ __('Achternaam*') }}"/>
                    <x-input id="lastname" class="block mt-1 w-full" type="text" name="lastname"
                             :value="old('lastname')" autocomplete="lastname"/>
                </div>
            </section>

            <div class="mt-4">
                <x-label for="birthdate" value="{{ __('Geboortedatum*') }}"/>
                <x-input id="birthdate" class="block mt-1 w-full" type="date" name="birthdate" :value="old('birthdate')"
                         required/>
            </div>

            <div class="mt-4">
                <x-label for="residence" value="{{ __('Woonplaats*') }}"/>
                <x-input id="residence" class="block mt-1 w-full" type="text" name="residence" :value="old('residence')"
                         required/>
            </div>

            <div class="mt-4">
                <x-label for="gender" value="{{ __('Geslacht*') }}"/>
                <x-select id="gender" class="block mt-1 w-full" name="gender" required>
                    <option value="male">{{ __('Man') }}</option>
                    <option value="female">{{ __('Vrouw') }}</option>
                </x-select>
            </div>

            <hr class="mt-4 border-gray-500"/>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Email*') }}"/>
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                         autocomplete="email"/>
            </div>

            <div class="mt-4">
                <x-label for="phone_number" value="{{ __('Telefoonnummer*') }}"/>
                <x-input id="phone_number" class="block mt-1 w-full" type="tel" name="phone_number"
                         :value="old('phone_number')" required autocomplete="phone_number"/>
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Wachtwoord*') }}"/>
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required
                         autocomplete="new-password"/>
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Bevestig wachtwoord*') }}"/>
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password"
                         name="password_confirmation" required autocomplete="new-password"/>
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required/>

                            <div class="ms-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                   href="{{ route('login') }}">
                    {{ __('Al geregistreerd?') }}
                </a>

                <x-button class="ms-4">
                    {{ __('Registreer') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
