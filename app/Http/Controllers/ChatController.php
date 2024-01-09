<?php

namespace App\Http\Controllers;

use App\Events\MessageDeleteEvent;
use App\Events\MessageEvent;
use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    //
    public function saveChat(Request $req)
    {
        $chat = Chat::create([
            'sender_id' => $req->sender_id,
            'receiver_id' => $req->receiver_id,
            'message' => $req->message
        ]);
        event(new MessageEvent($chat));
        return response()->json(['success' => true, 'data' => $chat]);
    }

    public function loadOldChats(Request $req)
    {
        $chats = Chat::where(function ($q) use ($req) {
            $q->where('sender_id', auth()->user()->id);
            $q->where('receiver_id', $req->receiver_id);
        })
            ->orWhere(function ($q) use ($req) {
                $q->where('sender_id', $req->receiver_id);
                $q->where('receiver_id', auth()->user()->id);
            })
            ->get();

        if (count($chats) > 0) {
            $html = '';
            foreach ($chats as $chat) {
                if ($chat->sender_id == auth()->user()->id) {
                    $html .= '<div id="chat-' . $chat->id . '" class="current-user-message">
                    <div class="current-user-info-box">
                        <div class="content">
                            <p class="message-content" >' . $chat->message . '</p>
                            <p class="message-date">' . $chat->created_at->format('d-m-Y h:i:s A') . '</p>
                        </div>
                        <div class="img">
                            <img width="50"
                        src="http://127.0.0.1:8000/dummy-user.png" alt="User Image">
                        </div>
                    </div>
                    <div><i  style="cursor:pointer" class="fa fa-trash text-danger delete-message" data-message-id="' . $chat->id . '" id="chat-' . $chat->id . '" data-message-body="' . $chat->message . '" > </i></div>

                </div>';
                } else {
                    $html .= '<div id="chat-' . $chat->id . '" class="recepient-user-message">
                    <div class="current-user-info-box">
                        <div class="img">
                            <img width="50"
                        src="http://127.0.0.1:8000/dummy-user.png" alt="User Image">
                        </div>
                        <div class="content">
                            <p class="message-content" >' . $chat->message . '</p>
                            <p class="">' . $chat->created_at->format('d-m-Y h:i:s A') . '</p>
                        </div>
                        
                    </div>
                </div>';
                }
            }
        } else {
            $html = '';
        }

        return response()->json(['success' => true, 'data' => $html]);
    }

    public function DeleteMessage(Request $req)
    {
        try {
            $chat = Chat::where('id', $req->message_id)->first();
            // dd($req->message_id);
            $chat->delete();
            event(new MessageDeleteEvent($req->message_id));
            return response()->json(['success' => true, 'data' => 'Message Deleted Successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => true, 'data' => $e->getMessage()]);
        }
    }
}
