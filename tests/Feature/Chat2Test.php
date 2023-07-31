<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class Chat2Test extends TestCase
{
    /**
     * A basic feature test example.
     */
    private $user_id = 1;
    private $chat_room_name = "test";
    public function test_example(): void
    {
        //チャットルームのデータ取得
        $response = $this->post('/api/getChatRoom', ['user_id' => $this->user_id]);
        $response->assertStatus(200);

        //新規作成したルームがちゃんと挿入されているか確認。
        $chat_Rooms = collect($response->decodeResponseJson()->json())->filter(function ($value, $key) {
            return $value['chat_room_name'] == "test";
        })->first();

        printf($chat_Rooms['chat_room_name']);

        $this->assertEquals($chat_Rooms['chat_room_name'], $this->chat_room_name);
    }
}
