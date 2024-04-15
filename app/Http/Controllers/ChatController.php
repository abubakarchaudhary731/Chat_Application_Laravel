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
    private ChatRepository $chat;

    public function __construct(ChatRepository $chat)
    {
        $this->chat = $chat;
    }

    public function index(Request $request, ?int $receiver_id = null)
    { 
        $userId = (int) $request->user()->id;
        $messages = $receiver_id ? $this->chat->getUserMessages($userId, (int) $receiver_id) : [];
        
        return Inertia::render('Chat/Home', [
            'messages' => $messages,
            'recentMessages' => $this->chat->getRecentUsersWithMessages($userId),
            'receiver' => User::find($receiver_id),
        ]);
    }

    public function store(Request $request, ?int $receiver_id = null)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        if (empty($receiver_id)) {
            return redirect()->route('chat.index'); // Redirect to default chat page
        }

        try {
            $this->chat->sendMessages([
                'sender_id' => (int) $request->user()->id,
                'receiver_id' => $receiver_id,
                'message' => $request->message,
            ]);

            event(new MessageSent($request->message, $request->user()->id, $receiver_id));

            return redirect()->route('chat.index', $receiver_id);
        } catch (\Throwable $th) {
            // Handle the exception (log it, notify the user, etc.)
            return redirect()->route('chat.index', $receiver_id)
                ->with('error', 'Failed to send message');
        }
    }
}
