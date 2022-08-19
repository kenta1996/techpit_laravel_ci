<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Article;
use App\User;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    public function testIsLikedByNull()
    {
        // Articleモデルがデータベースに保存
        $article = factory(Article::class)->create();
        // 引数にnullを渡してisLikedByを実施
        $result = $article->isLikedBy(null);
        $this->assertFalse($result);
    }

    //自分がログインしていいねした場合→true
    public function testIsLikedByTheUser()
    {
        $article = factory(Article::class)->create();
        $user = factory(User::class)->create();
        // likesテーブルのuser_idには、$userのidの値
        // likesテーブルのarticle_idには、$articleのidの値
        // を持った、likesテーブルのレコードが新規登録されます。
        // →「ファクトリで生成された$userが」「ファクトリで生成された$articleを」いいねしている
        $article->likes()->attach($user);

        $result = $article->isLikedBy($user);
        $this->assertTrue($result);
    }

    //自分がログインしていいねしていない場合→false
    public function testIsLikedByAnother()
    {
        $article = factory(Article::class)->create();
        $user = factory(User::class)->create();
        $another = factory(User::class)->create();
        $article->likes()->attach($another);

        $result = $article->isLikedBy($user);
        $this->assertFalse($result);
    }
}
