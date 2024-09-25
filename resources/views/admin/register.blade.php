<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Registratie') }}
        </h2>
    </x-slot>
    <div class="max-w-lg mx-auto py-10">
        <form method="POST" class="md:col-span-2">
            @csrf
            <div class="px-4 py-5 bg-white dark:bg-gray-800 sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                <!-- Name -->
                <div class="col-span-6 sm:col-span-4 flex gap-x-2">
                    <div>
                        <x-label for="firstname" value="{{ __('Voornaam*') }}"/>
                        <x-input id="firstname" type="text" class="mt-1 block w-full" name="firstname" :value="old('firstname')" required />
                        <x-input-error for="firstname" class="mt-2" />
                    </div>
                    <div>
                        <x-label for="middlename" value="{{ __('Tussenvoegsel') }}"/>
                        <x-input id="middlename" type="text" class="mt-1 block w-full" name="middlename" :value="old('middlename')" />
                    </div>
                    <div>
                        <x-label for="lastname" value="{{ __('Achternaam*') }}"/>
                        <x-input id="lastname" type="text" class="mt-1 block w-full" name="lastname" :value="old('lastname')" required />
                        <x-input-error for="lastname" class="mt-2" />
                    </div>
                </div>

                <!-- Email -->
                <div class="mt-4">
                    <x-label for="email" value="{{ __('Email*') }}"/>
                    <x-input id="email" type="email" class="mt-1 block w-full" name="email" :value="old('email')" required />
                    <x-input-error for="email" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-label for="password" value="{{ __('Wachtwoord*') }}"/>
                    <x-input id="password" class="block mt-1 w-full" type="password" name="password" required
                             autocomplete="new-password"/>
                    <x-input-error for="password" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-label for="password_confirmation" value="{{ __('Bevestig wachtwoord*') }}"/>
                    <x-input id="password_confirmation" class="block mt-1 w-full" type="password"
                             name="password_confirmation" required autocomplete="new-password"/>
                </div>
            </div>

            <div class="flex items-center justify-end px-4 py-3 bg-gray-50 dark:bg-gray-800 text-end sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 transition ease-in-out duration-150">
                    Save
                </button>
            </div>
        </form>
    </div>

</x-app-layout>
