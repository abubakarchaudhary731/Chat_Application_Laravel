<?php

namespace App\Repositories;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;



class ChatRepository
{
    public function getUserMessages(int $sender_id, int $receiver_id) 
    {
        return Message::whereIn('sender_id', [$sender_id, $receiver_id])
            ->whereIn('receiver_id', [$sender_id, $receiver_id])
            ->get();
    }
    
    public function getRecentUsersWithMessages(int $sender_id): array
    {
        DB::statement("SET SESSION sql_mode=''");

        $recentMessages = Message::where(function ($query) use ($sender_id) {
            $query->where('sender_id', $sender_id)
            ->orWhere('receiver_id', $sender_id);   
        })->groupBy('sender_id', 'receiver_id')
            ->select('sender_id', 'receiver_id', 'message', 'created_at')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc') 
            ->limit(30)
            ->get();

        return $this->getFilterRecentMessages($recentMessages, $sender_id);
    }
    public function sendMessages(array $data): Message
    {
        return Message::create($data);
    }

    public function getFilterRecentMessages(Collection $recentMessages, int $sender_id): array
    {
        $recentUsersWithMessage = [];
        $usedUserIds = [];
    
        foreach ($recentMessages as $message) {
            $userId = $message->sender_id == $sender_id ? $message->receiver_id : $message->sender_id;
    
            if (!in_array($userId, $usedUserIds)) {
                $latestMessage = Message::where(function ($query) use ($userId, $sender_id) {
                    $query->where(function ($q) use ($userId, $sender_id) {
                        $q->where('sender_id', $sender_id)
                            ->where('receiver_id', $userId);
                    })->orWhere(function ($q) use ($userId, $sender_id) {
                        $q->where('sender_id', $userId)
                            ->where('receiver_id', $sender_id);
                    });
                })->orderBy('created_at', 'desc')->first();
    
                $recentUsersWithMessage[] = [
                    'user_id' => $userId,
                    'message' => $latestMessage
                ];
    
                $usedUserIds[] = $userId;
            }
        }
    
        foreach ($recentUsersWithMessage as $key => $userMessage) {
            $recentUsersWithMessage[$key]['name'] = User::where('id', $userMessage['user_id'])->first()->name ?? '';
        }
    
        return $recentUsersWithMessage;
    }
    
}
