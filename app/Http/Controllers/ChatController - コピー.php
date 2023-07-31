<?php

namespace App\Http\Controllers;

use App\Models\ChatContent;
use App\Models\ChatRoom;
use App\Models\ChatUser;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    //チャットルームの新規作成
    public function createChatRoom(Request $request, ChatRoom $chatRoom)
    {
        $chatRoom->fill($request->all());
        $chatRoom->save();
        return response()->json(null, 201);
    }

    //チャットルームのデータ取得
    public function getChatRoom(Request $request)
    {
        $chatRoom = ChatRoom::all();
        return response()->json($chatRoom);
    }

    //チャットルームの参加
    public function joinChatRoom(Request $request, ChatUser $chatUser)
    {
        $chatUser->fill($request->all());
        $chatUser->save();
        $chatUser2 = chatUser::all();
        return response()->json($chatUser2, 201);
    }

    //チャットルームからの脱退
    public function exitChatRoom(Request $request)
    {
        ChatUser::where("user_id", "=", $request->user_id)->where("chat_room_id", "=", $request->chat_room_id)->delete();
    }

    //チャットルームの削除
    public function deleteChatRoom(Request $request)
    {
        $chatRoom = ChatRoom::find($request->id);
        $chatRoom->delete();
    }

    //チャット取得
    public function getChat(Request $request)
    {
        $chatContent = ChatContent::all();
        return response()->json($chatContent);
    }

    //新規チャット投稿
    public function addChat(Request $request, ChatContent $chatContent)
    {
        $chatContent->fill($request->all());
        $chatContent->save();
        return response()->json(null, 201);
    }

    //チャット削除
    public function deleteChat(Request $request)
    {
        $chatContent = ChatContent::find($request->id);
        $chatContent->delete();
    }
}
