<?php

namespace Tests\Feature;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Tests\TestCase;

class ApiPostsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $baseData;

    protected const RECORDS = 5;

    public function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(new Carbon('2025-03-15 09:00:00'));

        Post::factory()->count(self::RECORDS)->create();

        $this->baseData = [
            'name' => Str::random(100),
            'description' => Str::random(280),
        ];
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function test_投稿一覧取得成功(): void
    {
        $response = $this->get('/api/posts');

        $response->assertStatus(200);
        $this->assertCount(Post::count(), $response->json());
        $response->assertJsonStructure(
            ['*' => ['id', 'name', 'date', 'description']]
        );
    }

    public function test_投稿成功(): void
    {
        Event::fake();

        $postData = $this->baseData;

        $response = $this->post('/api/posts', $postData);

        $response->assertStatus(201);
        $response->assertJsonStructure(
            ['status', 'message', 'errors']
        );

        // DB登録内容のアサーション
        $storeData = Post::latest()->orderBy('id', 'DESC')->first();
        $this->assertEquals($postData['name'], $storeData->name);
        $this->assertEquals(now(), $storeData->date);
        $this->assertEquals($postData['description'], $storeData->description);

        // レスポンス内容のアサーション
        $this->assertTrue($response->json('status'));
        $this->assertEquals('登録されました。', $response->json('message'));
        $this->assertNull($response->json('errors'));
    }

    public function test_バリデーションエラー_名前なし(): void
    {
        $postData = [
            'name' => null,
        ] + $this->baseData;

        $response = $this->post('/api/posts', $postData);

        $this->assertValidationErrorResponse($response);

        $this->assertEquals(self::RECORDS, Post::count());
    }

    public function test_バリデーションエラー_名前字数オーバー(): void
    {
        $postData = [
            'name' => Str::random(101),
        ] + $this->baseData;

        $response = $this->post('/api/posts', $postData);

        $this->assertValidationErrorResponse($response);

        $this->assertEquals(self::RECORDS, Post::count());
    }

    public function test_バリデーションエラー_投稿内容なし(): void
    {
        $postData = [
            'description' => null,
        ] + $this->baseData;

        $response = $this->post('/api/posts', $postData);

        $this->assertValidationErrorResponse($response);

        $this->assertEquals(self::RECORDS, Post::count());
    }

    public function test_バリデーションエラー_投稿内容字数オーバー(): void
    {
        $postData = [
            'description' => Str::random(281),
        ] + $this->baseData;

        $response = $this->post('/api/posts', $postData);

        $this->assertValidationErrorResponse($response);

        $this->assertEquals(self::RECORDS, Post::count());
    }

    public function test_DBアクセスエラー(): void
    {
        // テスト用のLaravelイベントリスナーを追加
        Event::listen('eloquent.saving: ' . Post::class, function () {
            throw new \PDOException('Database error');
        });

        $postData = $this->baseData;

        $response = $this->post('/api/posts', $postData);

        $response->assertStatus(500);
        $response->assertJsonStructure(
            ['status', 'message', 'errors']
        );
        $this->assertFalse($response->json('status'));
        $this->assertEquals('問題が発生しました。', $response->json('message'));
        $this->assertNull($response->json('errors'));
    }

    private function assertValidationErrorResponse($response): void
    {
        $response->assertStatus(422);
        $response->assertJsonStructure(
            ['status', 'message', 'errors']
        );
        $this->assertFalse($response->json('status'));
        $this->assertEquals('バリデーションエラーです。', $response->json('message'));
        $this->assertNotNull($response->json('errors'));
    }
}
