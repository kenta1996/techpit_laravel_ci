<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class ArticleControllerTest extends TestCase
{
    // データベースをリセットするトレイト
    use RefreshDatabase;

    public function testIndex()
    {
        // Illuminate\Foundation\Testing\TestResponseクラスを返す
        $response = $this->get(route('articles.index'));
        // assertStatusメソッドの引数には、HTTPレスポンスのステータスコードを渡します。
            // 200であればテストに合格
            // 200以外であればテストに不合格
        // assertViewIsはarticles.indexが表示されているかテスト
        $response->assertStatus(200)
            ->assertViewIs('articles.index');
    }

    public function testGuestCreate()
    {
        $response = $this->get(route('articles.create'));
        // assertRedirectは、引数として渡したURLにリダイレクトされたかどうかをテスト
        $response->assertRedirect(route('login'));
    }

    public function testAuthCreate()
    {
        // factory_テストに必要なモデルのインスタンスを、ファクトリというものを利用して生成できます。
        $user = factory(User::class)->create();
        // actingAs_ログインした状態を作り出します
        $response = $this->actingAs($user)
            ->get(route('articles.create'));
        $response->assertStatus(200)
            ->assertViewIs(('articles.create'));
    }
}
