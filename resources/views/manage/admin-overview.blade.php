<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin manager') }}
        </h2>
    </x-slot>

    <div class="py-8 flex flex-col items-center">
        <div class="gray-700 dark:text-gray-300 dark:bg-gray-800 lg:max-w-7xl w-full py-2 px-4 rounded-md">
            <div class="text-red-500 {{ count($errors) ? 'mb-4' : '' }}">
                @foreach($errors->getMessages() as $error)
                    @foreach($error as $message)
                        <p>{{ $message }}</p>
                    @endforeach
                @endforeach
            </div>

            @if(session()->has('success'))
                <p class="text-green-400 mb-4">{{ session()->get('success') }}</p>
            @endif

            <div class="mt-2">
                <a href="{{ route('manage.admin.create') }}">
                    <x-button>Registreer admin</x-button>
                </a>
            </div>

            <br />

            <div class="overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3">Naam</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Functie</th>
                        <th class="px-6 py-3">Acties</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i = 0; ?>
                    @foreach($users as $user)
                        @if($user->role === 'user')
                            @continue
                        @endif
                        <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $user->firstname}} {{$user->middlename}} {{$user->lastname}}</td>
                            <td class="px-6 py-4">{{ $user->email }}</td>
                            <td class="px-6 py-4">{{ ucfirst($user->role) }}</td>
                            <td class="px-6 py-4">
                                <x-modal-button for="modal-delete-{{$i}}"
                                                class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md
                                                        font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700
                                                        focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800
                                                        transition ease-in-out duration-150 disabled:opacity-50"
                                                :disabled="auth()->user()->id === $user->id">
                                    Verwijder
                                </x-modal-button>
                            </td>
                        </tr>

                        @if(auth()->user() !== $user)
                            <x-admin-modal id="modal-delete-{{$i}}" class="px-8">
                                <h1 class="text-4xl">Account verwijderen</h1>
                                <p class="text-lg mt-4">Weet je zeker dat je: {{ $user->firstname }} wilt verwijderen?</p>
                                <form method="POST" action="{{ route('manage.admins.delete', $user) }}">
                                    @method('DELETE')
                                    @csrf
                                    <x-danger-button type="submit" class="float-end">
                                        Verwijderen
                                    </x-danger-button>
                                </form>
                                <x-button class="float-end mr-2" @click="closeModal('modal-delete-{{$i}}')">
                                    Annuleren
                                </x-button>
                            </x-admin-modal>
                                <?php $i++; ?>
                        @endif

                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
