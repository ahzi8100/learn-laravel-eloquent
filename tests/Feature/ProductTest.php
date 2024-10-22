<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CommentSeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\ImageSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\TagSeeder;
use Database\Seeders\VoucherSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;

class ProductTest extends TestCase
{
    public function testOneToMany()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $product = Product::find('1');
        self::assertNotNull($product);

        $category = $product->category;
        self::assertNotNull($category);
        self::assertEquals('FOOD', $category->id);
    }

    public function testHasOneOfMany()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $category = Category::find('FOOD');
        self::assertNotNull($category);

        $cheapestProduct = $category->cheapestProduct;
        self::assertNotNull($cheapestProduct);
        assertEquals('1', $cheapestProduct->id);

        $mostExpansiveProduct = $category->mostExpensiveProduct;
        self::assertNotNull($mostExpansiveProduct);
        self::assertEquals('2', $mostExpansiveProduct->id);
    }

    public function testOneToOnePolymorphic()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, ImageSeeder::class]);

        $product = Product::find('1');
        assertNotNull($product);

        $image = $product->image;
        assertNotNull($image);
        assertEquals('image2.com', $image->url);
    }

    public function testOneToManyPolymorphic()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, VoucherSeeder::class, CommentSeeder::class]);
        $product = Product::find('1');
        assertNotNull($product);

        $comments = $product->comments;
        foreach ($comments as $comment) {
            assertEquals('product', $comment->commentable_type);
            assertEquals($product->id, $comment->commentable_id);
        }

    }

    public function testOneOfManyPolymorphic()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, VoucherSeeder::class, CommentSeeder::class]);
        $product = Product::find('1');
        assertNotNull($product);

        $comments = $product->latestComment;
        assertNotNull($comments);

        $comments = $product->oldestComment;
        assertNotNull($comments);
    }

    public function testManyToManyPolymorphic()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, VoucherSeeder::class, TagSeeder::class]);
        $product = Product::find('1');
        $tags = $product->tags;
        assertNotNull($tags);
        assertCount(1, $tags);

        foreach ($tags as $tag) {
            assertNotNull($tag->id);
            assertNotNull($tag->name);

            $vouchers = $tag->vouchers;
            assertNotNull($vouchers);
            assertCount(1, $vouchers);
        }
    }

    public function testEloquentCollection()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $products = Product::get();
        $products = $products->toQuery()->where('price', 200)->get();

        assertNotNull($products);
        assertEquals('2', $products[0]->id);
    }

    public function testSerialization()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $products = Product::get();
        assertCount(2, $products);

        $json = $products->toJson(JSON_PRETTY_PRINT);
        Log::info($json);
    }

    public function testSerializationRelation()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, ImageSeeder::class]);

        $products = Product::get();
        $products->load(['category', 'image']);
        assertCount(2, $products);

        $json = $products->toJson(JSON_PRETTY_PRINT);
        Log::info($json);
    }
}

