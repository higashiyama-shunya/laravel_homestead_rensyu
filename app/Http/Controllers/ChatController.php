<?php

namespace App\Http\Controllers;

use App\Models\ChatContent;
use App\Models\ChatRoom;
use App\Models\ChatUser;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    //チャットルームの新規作成
    public function createChatRoom(Request $request, ChatRoom $chatRoom)
    {
        $chatRoom->chat_room_name = $request->input('chat_room_name');
        $chatRoom->owner_id = $request->user()->id;
        $chatRoom->save();
        return response()->json(null, 201);
    }

    //チャットルームの招待
    public function inviteChatRoom(Request $request)
    {
        //
    }

    //チャットルームのデータ取得
    public function getChatRoom(Request $request)
    {
        $chatRoom = ChatRoom::where("owner_id", $request->user()->id)->get();
        if ($chatRoom == null) {
            $chatUser = ChatUser::where("user_id", $request->user()->id)->get();
            if ($chatUser != null) {
                $chatRoom = ChatRoom::where("id", $chatUser->chat_room_id)->get();
            }
        }
        return response()->json($chatRoom);
    }

    //テスト用　全チャットルームのデータ取得
    public function getAllChatRoom()
    {
        $chatRoom = ChatRoom::all();
        return response()->json($chatRoom);
    }

    //チャットルームの参加
    public function joinChatRoom(Request $request, ChatUser $chatUser)
    {
        $chatUser->user_id = $request->user()->id;
        $chatUser->chat_room_id = $request->input('chat_room_id');
        $chatUser->save();
        return response()->json(null, 201);
    }

    //チャットルームからの脱退
    public function exitChatRoom(Request $request)
    {
        ChatUser::where("user_id", "=", $request->user()->id)->where("chat_room_id", "=", $request->chat_room_id)->delete();
    }

    //チャットルームの削除
    public function deleteChatRoom(Request $request)
    {
        ChatRoom::where("owner_id", $request->user()->id)->where("chat_room_name", $request->chat_room_name)->delete();
    }

    //新規チャット投稿
    public function addChat(Request $request, ChatContent $chatContent)
    {
        $chatContent->user_id = $request->user()->id;
        $chatContent->chat_room_id = $request->input('chat_room_id');
        $chatContent->chat = $request->input('chat');
        $chatContent->save();
        return response()->json(null, 201);
    }

    //チャット取得
    public function getChat(Request $request)
    {
        $chatContent = ChatContent::where("chat_room_id", $request->input('chat_room_id'))->get();
        return response()->json($chatContent);
    }

    //チャット削除
    public function deleteChat(Request $request)
    {
        ChatContent::where("user_id", $request->user()->id)->where("id", $request->input('id'))->delete();
    }
}
