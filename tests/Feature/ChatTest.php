<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ChatTest extends TestCase
{
    private $chat_room_name = "test";
    private $user_id = 1;
    private $chat_room_id = 3;
    private $chat = "hello";

    public function test_chat(): void
    {
        //確認用のルームネーム。
        $new_chat_room_name = $this->chat_room_name . time();
        printf("name:" . $new_chat_room_name . "\n");
        //チャットルーム新規作成
        $response = $this->post('/api/createChatRoom', ['chat_room_name' => $new_chat_room_name]);
        $response->assertStatus(201);

        $char_room_list =

            //チャットルームのデータ取得
            $response = $this->post('/api/getChatRoom', ['user_id' => $this->user_id]);
        $response->assertStatus(200);

        //新規作成したルームがちゃんと挿入されているか確認。
        $chat_Rooms = collect($response->decodeResponseJson()->json())->filter(function ($value, $key) use ($new_chat_room_name) {
            return $value['chat_room_name'] == $new_chat_room_name;
        })->first();

        printf("getname:" . $new_chat_room_name . "\n");
        printf("debugid:" . $chat_Rooms['id'] . "\n");
        printf("debugname:" . $chat_Rooms['chat_room_name'] . "\n");

        $this->assertEquals($chat_Rooms['chat_room_name'], $new_chat_room_name);


        //チャットルームの参加
        $new_user_id = $this->user_id . rand(1, 100);
        $new_chat_room_id = $this->chat_room_id . rand(1, 100);
        printf("user_id:" . $new_user_id . "\n");
        printf("chat_room_id:" . $new_chat_room_id . "\n");
        $response = $this->post('/api/joinChatRoom', ['user_id' => $new_user_id, 'chat_room_id' => $new_chat_room_id]);
        $response->assertStatus(201);

        //チャットルームに参加できているか確認。
        $chat_Users = collect($response->decodeResponseJson()->json())->filter(function ($value, $key) use ($new_user_id, $new_chat_room_id) {
            return $value['chat_room_id'] == $new_chat_room_id && $value['user_id'] == $new_user_id;
        })->first();

        $this->assertEquals($chat_Users['chat_room_id'], $new_chat_room_id);
        $this->assertEquals($chat_Users['user_id'], $new_user_id);
        printf("get_room_id:" . $chat_Users['chat_room_id'] . "\n");
        printf("get_user_id:" . $chat_Users['user_id'] . "\n");

        //チャットルームからの脱退
        $response = $this->post('/api/exitChatRoom', ['user_id' => $new_user_id, 'chat_room_id' => $new_chat_room_id]);
        $response->assertStatus(200);

        $this->assertDatabaseMissing('chat_users', ['user_id' => $new_user_id, 'chat_room_id' => $new_chat_room_id]);

        //チャットルームの削除
        $response = $this->post('/api/deleteChatRoom', ['id' => $chat_Rooms['id']]);
        $response->assertStatus(200);

        $this->assertDatabaseMissing('chat_rooms', ['id' => $chat_Rooms['id']]);

        //チャット取得
        $response = $this->post('/api/getChat', ['user_id' => $this->user_id]);
        $response->assertStatus(200);

        //新規チャット投稿
        //可変する入力するチャットの用意
        $new_chat = $this->chat . time();
        printf("chat:" . $new_chat . "\n");

        $response = $this->post('/api/addChat', ['chat_room_id' => $this->chat_room_id, 'user_id' => $this->user_id, 'chat' => $new_chat]);
        $response->assertStatus(201);

        //チャットが投稿されているか確認。
        $response = $this->post('/api/getChat', ['user_id' => $this->user_id]);
        $response->assertStatus(200);

        $chat_Contents = collect($response->decodeResponseJson()->json())->filter(function ($value, $key) use ($new_chat) {
            return $value['chat_room_id'] == $this->chat_room_id && $value['user_id'] == $this->user_id && $value['chat'] == $new_chat;
        })->first();

        $this->assertEquals($chat_Contents['chat_room_id'], $this->chat_room_id);
        $this->assertEquals($chat_Contents['user_id'], $this->user_id);
        $this->assertEquals($chat_Contents['chat'], $new_chat);

        printf('getchat:' . $chat_Contents['chat']);

        //チャット削除
        $response = $this->post('/api/deleteChat', ['id' => $chat_Contents['id']]);
        $response->assertStatus(200);

        $this->assertDatabaseMissing('chat_contents', ['id' => $chat_Contents['id']]);
    }
}
