<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\ChatRoom;
use Tests\TestCase;

class CreateChatRoomTest extends TestCase
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


    public function test_create_chat_room()
    {
        $chat_room_name = 'test:' . time();
        $response = $this->actingAs($this->user)->post('api/createChatRoom/', ['user' => $this->user, 'chat_room_name' => $chat_room_name]);
        $chat_room_list = ChatRoom::where("owner_id", $this->user->id)->get();
        $this->assertEquals(count($chat_room_list), 1);
        $this->assertEquals($chat_room_list[0]->chat_room_name, $chat_room_name);
        printf("POST_room_name:" . $chat_room_name . "\n");
        printf("GET_room_name:" . $chat_room_list[0]->chat_room_name . "\n");

        $response->assertStatus(201);

        //データ取得が上手くいくかどうか判定する。GetChatRoomメソッドのテスト。
        $response = $this->actingAs($this->user)->post('api/getChatRoom/', ['user' => $this->user])->assertStatus(200);
        $chat_get_room = collect($response->decodeResponseJson()->json())->filter(function ($value, $key) use ($chat_room_name) {
            return $value['chat_room_name'] == $chat_room_name;
        })->first();
        printf("owner_id:" . $chat_get_room['owner_id'] . "\n");
        printf("chat_room_name" . $chat_get_room['chat_room_name'] . "\n");
        $this->assertEquals($chat_get_room['owner_id'], $this->user->id);
        $response->assertStatus(200);
    }
}
