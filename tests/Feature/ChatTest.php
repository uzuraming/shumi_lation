<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChatTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;
    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(\App\User::class)->create(); //一般ユーザー
        $this->user2 = factory(\App\User::class)->create(); //一般ユーザー
    }



    public function testSendChatRequest()
    {

        $id = strval($this->user2->id);

        $response = $this->actingAs($this->user)->json('POST', route('users.send_request',['id'=>$id, 'message'=>'test']));
        $response->assertStatus(200);
    }
    public function testDeleteChatRequest()
    {

        $id = strval($this->user2->id);

        $this->actingAs($this->user)->json('POST', route('users.send_request',['id'=>$id, 'message'=>'test']));
        $response = $this->actingAs($this->user)->json('POST', route('users.destory_request',['id'=>$id, 'message'=>'test']));
        $response->assertStatus(200);
    }

    public function testAcceptChatRequest()
    {

        $id = strval($this->user2->id);

        $this->actingAs($this->user)->json('POST', route('users.send_request',['id'=>$id, 'message'=>'test']));
        $response = $this->actingAs($this->user)->json('POST', route('users.destory_request',['id'=>$id, 'message'=>'test']));
        $response->assertStatus(200);
    }
}
