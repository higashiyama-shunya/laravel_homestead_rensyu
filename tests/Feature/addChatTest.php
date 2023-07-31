<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\ChatContent;
use Tests\TestCase;

class addChatTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_add_chat(): void
    {
        //チャット投稿のテスト
        $test_chat = 'test:' . time();
        printf("testchat:" . $test_chat . "\n");
        $test_chat_room_id = 1;
        $response = $this->actingAs($this->user)->post('api/addChat', ['user' => $this->user, 'chat' => $test_chat, 'chat_room_id' => $test_chat_room_id]);
        $chat_list = ChatContent::where("chat", $test_chat)->get();
        $this->assertEquals(count($chat_list), 1);
        $this->assertEquals($chat_list[0]->chat_room_id, $test_chat_room_id);
        printf("GET_chat:" . $chat_list[0]->chat . "\n");
        printf("GET_chat_room_id:" . $chat_list[0]->chat_room_id . "\n");

        $response->assertStatus(201);
        //データ取得が上手くいくかどうか判定する。　getChatメソッド
        $response = $this->actingAs($this->user)->post('api/getChat', ['chat_room_id' => $test_chat_room_id]);
        $get_chat = collect($response->decodeResponseJson()->json())->filter(function ($value, $key) use ($test_chat) {
            return $value['chat'] == $test_chat;
        })->first();
        printf("get_chat:" . $get_chat['chat'] . "\n");
        printf("get_chat_room_id:" . $get_chat['chat_room_id']);
        $this->assertEquals($get_chat['user_id'], $this->user->id);
        $response->assertStatus(200);
    }
}
