<?php

namespace Tests\Feature;

use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use function PHPUnit\Framework\assertNotNull;

class CommentTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testCreateComment()
    {
        $comment = new Comment();
        $comment->email = 'exp@mail.com';
        $comment->title = 'Sample Title';
        $comment->comment = 'Sample Comment';
        $comment->commentable_id = '1';
        $comment->commentable_type = 'product';
        $comment->save();

        self::assertNotNull($comment->comment);
    }

    public function testDefaultAttributesValues()
    {
        $comment = new Comment();
        $comment->email = 'exp@mail.com';
        $comment->commentable_id = '1';
        $comment->commentable_type = 'product';
        $comment->save();

        assertNotNull($comment->title);
        assertNotNull($comment->comment);
    }
}
