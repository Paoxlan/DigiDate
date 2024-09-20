<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tag toevoegen') }}
        </h2>
    </x-slot>

    <div class="py-8 flex flex-col items-center">
        <div class="gray-700 dark:text-gray-300 dark:bg-gray-800 lg:max-w-7xl w-full py-2 px-4 rounded-md">
            <div class="w-full border-gray-600 border-[2px] mt-2 ml">
                <form action="{{ route('manage.tags.store') }}" method="POST">
                    @csrf


                    <div class="mb-4">
                        <x-input-error for="error" class="flex justify-center mt-4 mb-4"/>

                        <h3 class="flex justify-center mt-4 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                            Tag toevoegen</h3>
                        <input type="text" name="name" id="name"
                               class="mx-auto mt-5 block rounded-md bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600"
                               placeholder="Voer een tagnaam in">

                        <div class="flex justify-center mt-4">
                            <x-button type="submit">
                                {{ __('Tag opslaan') }}
                            </x-button>
                        </div>
                    </div>
                </form>

                <tbody class="bg-gray-700">
                </tbody>
            </div>
        </div>
    </div>
</x-app-layout>
