<?php

namespace App\Http\Controllers;

use App\Models\ChatUser;
use Illuminate\Http\Request;

class ChatUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $chat_User = ChatUser::where("user_id", "=", $request->user_id)->get();

        return response()->json($chat_User);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ChatUser $chat_User)
    {
        $chat_User->fill($request->all());
        $chat_User->save();
        return response()->json(null, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ChatUser $chatUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChatUser $chatUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChatUser $chatUser)
    {
        //
    }
}
