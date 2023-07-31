<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\ChatRoom;
use App\Models\ChatUser;
use App\Models\User;
use Tests\TestCase;

class DeleteChatRoomTest extends TestCase
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

    public function test_delete_chat_room(): void
    {
        $chat_room_name = 'test:' . time();
        $response = $this->actingAs($this->user)->post('api/createChatRoom/', ['user' => $this->user, 'chat_room_name' => $chat_room_name]);
        $chat_room_list = ChatRoom::where("owner_id", $this->user->id)->get();
        $this->assertEquals(count($chat_room_list), 1);
        $this->assertEquals($chat_room_list[0]->chat_room_name, $chat_room_name);
        printf("POST_room_name:" . $chat_room_name . "\n");
        printf("GET_room_name:" . $chat_room_list[0]->chat_room_name . "\n");

        $response->assertStatus(201);

        //DeleteChatRoomのテスト
        $response = $this->actingAs($this->user)->post('api/deleteChatRoom', ['user' => $this->user, 'chat_room_name' => $chat_room_name]);
        $chat_room_list = ChatRoom::where("owner_id", $this->user->id)->where("chat_room_name", $chat_room_name)->get();
        printf(count($chat_room_list));
        $this->assertEquals(count($chat_room_list), 0);
        $response->assertStatus(200);
    }
}
