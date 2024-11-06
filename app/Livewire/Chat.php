<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class Chat extends Component
{
    public User $chattingWith;
    public ?string $content = "";

    function mount(User $chattingWith): void {
        $this->chattingWith = $chattingWith;
    }

    /**
     * @return Message[]|Collection
     */
    public function getMessages(): array|Collection
    {
        return Message::getMessagesFrom(auth()->user(), $this->chattingWith);
    }

    public function sendMessage(): void
    {
        if (empty($this->content)) return;

        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $this->chattingWith->id,
            'content' => $this->content
        ]);

        $this->content = "";
    }

    public function getUserProperty(): User
    {
        return auth()->user();
    }

    public function render(): View
    {
        return view('livewire.chat');
    }
}
