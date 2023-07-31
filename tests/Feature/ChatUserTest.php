<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChatUserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    private $user_id = 2;
    private $chat_room_id = 1;
    public function test_example(): void
    {
        //$response = $this->post('/api/chat_users', ['user_id' => $this->user_id, 'chat_room_id' => $this->chat_room_id]);
        //$response->assertStatus(201);

        $response = $this->get('/api/chat_users', ['user_id' => $this->user_id]);
        $response->assertStatus(200);

        $chat_Users = collect($response->decodeResponseJson()->json())->filter(function ($value, $key) {
            return $value['user_id'] == $this->user_id && $value['chat_room_id'] == $this->chat_room_id;
        })->first();
        //データを持ってこれずにnullになってしまっている。
        //下のassertEqualsでエラーが出る21行目のgetで上手く取得できていない？
        $this->assertEquals($chat_Users['user_id'], $this->user_id);
        $this->assertEquals($chat_Users['chat_room_id'], $this->chat_room_id);
    }
}
