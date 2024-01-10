<?php

namespace App\Repositories;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ChatRepository
{
    public function getUserMessages(int $sender_id, int $receiver_id): Collection
    {
        return Message::whereIn('sender_id', [$sender_id, $receiver_id])
            ->whereIn('receiver_id', [$sender_id, $receiver_id])
            ->get();
    }

    public function getRecentUsersWithMessages(int $sender_id): array
    {
        DB::statement("SET SESSION sql_mode=''");

        $recentMessages = Message::select('sender_id', 'receiver_id', 'message', 'created_at')
            ->whereIn('sender_id', [$sender_id])
            ->orWhereIn('receiver_id', [$sender_id])
            ->orderBy('created_at', 'desc')
            ->limit(30)
            ->get();

        return $this->getFilteredRecentMessages($recentMessages, $sender_id);
    }

    public function sendMessages(array $data): Message
    {
        return Message::create($data);
    }

    public function getFilteredRecentMessages(Collection $recentMessages, int $sender_id): array
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
                })->latest('created_at')->first();

                $recentUsersWithMessage[] = [
                    'user_id' => $userId,
                    'message' => $latestMessage,
                ];

                $usedUserIds[] = $userId;
            }
        }

        $userIds = array_column($recentUsersWithMessage, 'user_id');
        $userNames = User::whereIn('id', $userIds)->pluck('name', 'id')->all();

        foreach ($recentUsersWithMessage as &$userMessage) {
            $userMessage['name'] = $userNames[$userMessage['user_id']] ?? '';
        }

        return $recentUsersWithMessage;
    }
}
