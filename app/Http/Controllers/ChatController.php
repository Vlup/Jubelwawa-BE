<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Http\Resources\UserResource;
use App\Models\Chat;
use App\Models\Message;
use App\Models\Property;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Hash;

class ChatController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $user = auth()->user();
        $chats = Chat::with('property')
            ->whereHas('lastMessage')
            ->where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->get();

        return ChatResource::collection($chats);
    }

    public function chatMessages($id): AnonymousResourceCollection
    {
        $chat = Chat::findOrFail($id);

        if ($chat->sender_id !== auth()->user()->id && $chat->receiver_id !== auth()->user()->id) {
            return response()->json([
                'status' => 'false',
                'message' => 'Forbidden'
            ], 403);
        }

        $messages = Message::with(['chat.property'])
            ->where('chat_id', $id)
            ->latest()
            ->get();

        return MessageResource::collection($messages);
    }

    public function sendMessage(Request $request)
    {
        $input = validator($request->all(), [
            'chat_id' => 'required|exists:chats,id',
            'text' => 'required|string'
        ])->validate();
        
        $message = new Message();
        $message->chat_id = $input['chat_id'];
        $message->user_id = auth()->user()->id;
        $message->text = $input['text'];
        $message->save();

        return response()->json([
            "status" => true,
            'message' => 'Send message successfully!',
        ]);
    }
}
