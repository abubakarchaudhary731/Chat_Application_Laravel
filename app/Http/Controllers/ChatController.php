<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\ChatRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Redirect;

class ChatController extends Controller
{
    public function __construct(private ChatRepository $chat)
    {
        $this->chat = $chat;
    }
    
    public function index(Request $request, ?int $receiver_id = null)
    {
        $messages = empty($receiver_id) ? [] : $this->chat->getUserMessages((int) $request->user()->id, (int) $receiver_id);
        return inertia::render('Chat/Home', [
            'messages' => $messages,
            'recentMessages' => $this->chat->getRecentUsersWithMessages($request->user()->id),
            'receiver' => User::find($receiver_id)
        ]);
    }

    public function store(Request $request, ?int $receiver_id = null)
    {
       $request->validate([
           'message' => 'required|string',
       ]);
        if (empty($receiver_id)) {
            return;
        }
        try {
            $this->chat->sendMessages([
                'sender_id' => (int) $request->user()->id,
                'receiver_id' => $receiver_id,
                'message' => $request->message,
            ]);
            return Redirect::route('chat.index', $receiver_id);
        } catch (\Throwable $th) {
            return Redirect::route('chat.index', $receiver_id);
        }
    }

}
