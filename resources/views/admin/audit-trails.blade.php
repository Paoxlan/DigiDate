<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Audit Trail') }}
        </h2>
    </x-slot>

    <div class="py-8 flex flex-col items-center">
        <div class="gray-700 dark:text-gray-300 dark:bg-gray-800 lg:max-w-7xl w-full py-2 px-4 rounded-md">
            <div class="overflow-x-auto shadow-md sm:rounded-lg max-w-7xl w-full">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3">{{ __('User') }}</th>
                        <th class="px-6 py-3">{{ __('Method') }}</th>
                        <th class="px-6 py-3">{{ __('Model') }}</th>
                        <th class="px-6 py-3">{{ __('Created At') }}</th>
                        <th class="px-6 py-3">{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @for($i = 0; $i < count($auditTrails); $i++)
                        @php ($auditTrail = $auditTrails[$i])
                        <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                            <td class="px-6 py-4 font-medium whitespace-nowrap {{ is_null($auditTrail->user) ? 'text-gray-400' : 'text-gray-900 dark:text-white' }}">
                                {{ $auditTrail->user?->full_name ?? '(deleted or automatic)' }}
                            </td>
                            <td class="px-6 py-4">
                                <x-audit-trail-method :method="$auditTrail->method"/>
                            </td>
                            <td class="px-6 py-4">{{ $auditTrail->class }}</td>
                            <td class="px-6 py-4">{{ $auditTrail->created_at }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('audit-trail', ['auditTrail' => $auditTrail]) }}">
                                    <x-button class="mr-2">{{ __('Show') }}</x-button>
                                </a>
                                <x-modal-button for="modal-delete-{{$i}}"
                                                class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md
                                    font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700
                                    focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800
                                    transition ease-in-out duration-150 disabled:opacity-50">
                                    {{ __('Delete') }}
                                </x-modal-button>
                            </td>
                        </tr>
                        <x-admin-modal id="modal-delete-{{$i}}" class="px-8">
                            <h1 class="text-4xl">{{ __('Delete record') }}</h1>
                            <p class="text-lg mt-4">{{ __('Are you sure you want to delete this record?') }}</p>
                            <form method="POST" action="{{ route('audit-trail.delete', ['auditTrail' => $auditTrail]) }}">
                                @method('DELETE')
                                @csrf
                                <x-danger-button type="submit" class="float-end">
                                    {{ __('Delete') }}
                                </x-danger-button>
                            </form>
                            <x-button class="float-end mr-2" @click="closeModal('modal-delete-{{$i}}')">
                                {{ __('Cancel') }}
                            </x-button>
                        </x-admin-modal>
                    @endfor
                    </tbody>
                </table>
                <div class="mt-2">
                    {{ $auditTrails->links() }}
                </div>
            </div>
        </div>

    </div>

</x-app-layout>
