<div class="flex flex-col overflow-y-scroll max-h-[calc(100vh-8rem)] relative" id="chat">
    <div class="p-4 flex-grow flex flex-col gap-y-2" wire:poll="getMessages">
        @foreach ($this->getMessages() as $message)
            <div class="flex items-start gap-2.5" id="{{ $message->sender_id === $this->user->id ? 'you' : 'them' }}">
                @if($message->sender_id === $this->user->id)
                    <div
                        class="flex flex-col w-full max-w-[320px] leading-1.5 p-4 border-gray-200 bg-gray-100 rounded-s-xl rounded-br-xl dark:bg-gray-700 ml-auto">
                        <div class="flex items-center space-x-2 rtl:space-x-reverse">
                            <span
                                class="text-sm font-semibold text-gray-900 dark:text-white">{{ $message->sender->full_name }}</span>
                            <span class="text-sm font-normal text-gray-500 dark:text-gray-400">{{ $message->getSendTime() }}</span>
                        </div>
                        <p class="text-sm font-normal py-2.5 break-all text-gray-900 dark:text-white">{{ $message->content }}</p>
                    </div>
                    <img class="w-8 h-8 rounded-full" src="{{ $message->sender->profile_photo_url }}"
                         alt="{{ $message->sender->firstname }}">
                @else
                    <img class="w-8 h-8 rounded-full" src="{{ $message->sender->profile_photo_url }}"
                         alt="{{ $message->sender->firstname }}">
                    <div
                        class="flex flex-col w-full max-w-[320px] leading-1.5 p-4 border-gray-200 bg-gray-100 rounded-e-xl rounded-es-xl dark:bg-gray-700">
                        <div class="flex items-center space-x-2 rtl:space-x-reverse">
                            <span
                                class="text-sm font-semibold text-gray-900 dark:text-white">{{ $message->sender->full_name }}</span>
                            <span class="text-sm font-normal text-gray-500 dark:text-gray-400">{{ $message->getSendTime() }}</span>
                        </div>
                        <p class="text-sm font-normal py-2.5 text-gray-900 break-words dark:text-white">{{ $message->content }}</p>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="fixed bottom-0 w-[calc(100%-16.666667%)] bg-gray-100 dark:bg-gray-800 flex py-1 px-2 gap-x-4">
        <x-input class="flex-grow" type="text" wire:model="content" wire:keydown.enter="sendMessage"/>
        <x-button wire:click="sendMessage">{{ __('Send') }}</x-button>
    </div>
    <script type="text/javascript">
        const chat = document.getElementById('chat');
        chat.scrollTop = chat.scrollHeight;

        chat.addEventListener('DOMNodeInserted', (e) => {
            const target = e.target;
            if (target?.id !== 'you' && target?.id !== 'them') return;
            if (target.id === 'you') {
                chat.scrollTop = chat.scrollHeight;
                return;
            }

            if (chat.scrollHeight - chat.scrollTop > 2000) return;

            chat.scrollTop = chat.scrollHeight;
        });
    </script>
</div>
