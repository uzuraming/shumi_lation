<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WorkTest extends TestCase
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



    // 作品のPOSTリクエスト
    public function testPostWorks()
    {
        $response = $this->actingAs($this->user)->json('POST', route('works.store',['title'=>'test','genre'=>'文学','content' => 'test', 'img_path'=>null]));;
        $response->assertStatus(200);
    }

    // 作品のUPDATEリクエスト
    // public function testUpdateWorks()
    // {
    //     $this->actingAs($this->user)->json('POST', route('works.store',['title'=>'test','genre'=>'文学','content' => 'test', 'img_path'=>null]));
    //     $id = $this->user->works()->first()->id;
    //     $response = $this->actingAs($this->user)->json('PUT', route('works.update',['id'=>$id,'title'=>'test','genre'=>'文学','content' => 'test', 'img_path'=>null]));;
    //     $response->assertStatus(200);
    // }

    // 作品のDELETEリクエスト
    // public function testDeleteWorks()
    // {
    //     $this->actingAs($this->user)->json('POST', route('works.store',['title'=>'test','genre'=>'文学','content' => 'test', 'img_path'=>null]));
    //     $id= Works::first()->id;
    //     $response = $this->actingAs($this->user)->json('delete', route('works.destroy',['id'=>$id]));;
    //     $response->assertStatus(200);
    // }
    



}
