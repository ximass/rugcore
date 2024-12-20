<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function createChat(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'knowledge_base_ids' => 'array',
            'knowledge_base_ids.*' => 'exists:knowledge_bases,id',
        ]);

        $chat = Chat::create([
            'name' => $request->input('name'),
            'user_id' => $request->user()->id,
        ]);

        $knowledgeBaseIds = $request->input('knowledge_base_ids', []);
        $chat->knowledgeBases()->sync($knowledgeBaseIds);

        return response()->json($chat);
    }

    public function getChats(Request $request)
    {
        $chats = Chat::with('lastMessage')
            ->where('user_id', $request->user()->id)
            ->get()
            ->map(function ($chat) {
                return [
                    'id' => $chat->id,
                    'name' => $chat->name,
                    'lastMessage' => $chat->lastMessage ? $chat->lastMessage->text : '',
                ];
            });

        return response()->json($chats);
    }

    public function getMessages(Chat $chat)
    {
        if ($chat->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = $chat->messages()->with('user')->get();

        return response()->json($messages);
    }

    public function sendMessage(Request $request, Chat $chat)
    {
        $message = Message::create([
            'chat_id' => $chat->id,
            'user_id' => $request->user()->id,
            'text' => $request->input('text'),
        ]);

        broadcast(new MessageSent($chat->id, $message->text));

        return response()->json(['status' => 'Message sent!']);
    }
}