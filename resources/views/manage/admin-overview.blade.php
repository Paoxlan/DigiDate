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

            <table class="w-full border-gray-600 border-[2px] mt-2">
                <thead class="bg-zinc-700">
                <tr class="text-left">
                    <th class="p-1 border-gray-600 border-[2px]">Naam</th>
                    <th class="p-1 border-gray-600 border-[2px]">Email</th>
                    <th class="p-1 border-gray-600 border-[2px]">Functie</th>
                    <th class="p-1 border-gray-600 border-[2px]">Acties</th>
                </tr>
                </thead>
                <tbody class="bg-gray-700">
                <?php $i = 0; ?>
                @foreach($users as $user)
                    @if($user->role === 'user')
                        @continue
                    @endif
                    <tr>
                        <td class="p-1 border-gray-600 border-[2px]">{{ $user->firstname}} {{$user->middlename}} {{$user->lastname}}</td>
                        <td class="p-1 border-gray-600 border-[2px]">{{ $user->email }}</td>
                        <td class="p-1 border-gray-600 border-[2px]">{{ $user->role }}</td>
                        <td class="p-1  border-gray-600 border-[2px] w-min">
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
                        <x-modal id="modal-delete-{{$i}}" class="px-8">
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
                        </x-modal>
                            <?php $i++; ?>
                    @endif

                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
