<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class LoginTest extends TestCase
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
    }


    // ----------------------------------------
    // ゲストユーザーが指定のapiを叩けるか
    // ----------------------------------------

    // タイムラインのGETリクエスト
    public function testIsCanGetTimelines()
    {
        $response = $this->json('GET', route('timelines.index'));;
        $response->assertStatus(200);
    }

   

    // 作品のGETリクエスト
    public function testIsCanGetWorks()
    {
        $response = $this->get('/api/works');
        $response->assertStatus(200);
    }

    // 作品のGETリクエスト
    public function testIsCanGetWorksDetail()
    {
      $this->actingAs($this->user)->post('api/works',['title'=> Str::random(10),'genre' =>'文学', 'content' => Str::random(10), 'img_path'=>null]);

        $response = $this->get('/api/works/1');
        $response->assertStatus(200);
    }


    // ユーザーのGETリクエスト
    public function testIsCanGetUser()
    {
        $id = strval($this->user->id);
        $response = $this->get('/api/users/'.$id);
        $response->assertStatus(200);
    }

    // ----------------------------------------
    // ゲストユーザーが指定のAPIを叩けないこと
    // ----------------------------------------

    // タイムラインのPOSTリクエスト
    public function testIsCanPostTimelines()
    {
        $response = $this->json('POST', route('timelines.store',['content' => 'test']));;
        $response->assertStatus(401);
    }
    // 作品のPOSTリクエスト
    public function testIsCanPostWorks()
    {
        $response = $this->json('POST', route('works.store',['title'=>'test','content' => 'test']));;
        $response->assertStatus(401);
    }

    // チャット一覧のGETリクエスト
    public function testIsCanGetChats()
    {
        $response = $this->json('GET', route('chats.index'));
        $response->assertStatus(401);
    }


    // ----------------------------------------
    // ユーザーが指定のapiを叩けるか
    // ----------------------------------------
    // タイムラインのPOSTリクエスト
    public function testIsCanLoginUserPostTimelines()
    {
        $response = $this->actingAs($this->user)->json('POST', route('timelines.store',['content' => 'test']));;
        $response->assertStatus(200);
    }
    // 作品のPOSTリクエスト
    public function testIsCanLoginUserPostWorks()
    {
        $response = $this->actingAs($this->user)->json('POST', route('works.store',['title'=>'test','genre'=>'文学','content' => 'test', 'img_path'=>null]));;
        $response->assertStatus(200);
    }

    // チャット一覧のGETリクエスト
    public function testIsCanLoginUserGetChats()
    {
        $response = $this->actingAs($this->user)->json('GET', route('chats.index'));
        $response->assertStatus(200);
    }
    

    


}
