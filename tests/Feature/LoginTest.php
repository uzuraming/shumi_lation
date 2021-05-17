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


    // // ユーザーのGETリクエスト
    // public function testIsCanGetUser()
    // {
    //     $response = $this->get('/api/users/2');
    //     $response->assertStatus(200);
    // }

  

    


}
