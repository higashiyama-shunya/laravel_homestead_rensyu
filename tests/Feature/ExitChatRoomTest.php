<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\ChatRoom;
use App\Models\ChatUser;
use Tests\TestCase;

class ExitChatRoomTest extends TestCase
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


    public function test_join_chat_room(): void
    {
        $chat_room_id = 1;
        $response = $this->actingAs($this->user)->post('api/joinChatRoom', ['user' => $this->user, 'chat_room_id' => $chat_room_id]);
        $chat_user_list = ChatUser::where("user_id", $this->user->id)->get();
        printf("user_id:" . $this->user->id . "\n");
        printf("get_user_id:" . $chat_user_list[0]->user_id . "\n");
        print("get_chat_room_id:" . $chat_user_list[0]->chat_room_id . "\n");
        $this->assertEquals(count($chat_user_list), 1);
        $this->assertEquals($chat_user_list[0]->user_id, $this->user->id);
        $response->assertStatus(201);

        //ExitChatRoomメソッドのテスト
        $response = $this->actingAs($this->user)->post('api/exitChatRoom', ['user' => $this->user, 'chat_room_id' => $chat_room_id]);
        $chat_user_list = ChatUser::where("user_id", $this->user->id)->get();
        $this->assertEquals(count($chat_user_list), 0);
        $response->assertStatus(200);
    }
}
