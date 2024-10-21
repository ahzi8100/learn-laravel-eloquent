<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Scopes\IsActiveScope;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertTrue;

class CategoryTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testInsert()
    {
        $category = new Category();
        $category->id = 'GADGET';
        $category->name = 'Gadget';
        $result = $category->save();

        $this->assertTrue($result);
    }

    public function testInserMany()
    {
        $categories = [];
        for ($i = 0; $i < 10; $i++) {
            $categories[] = [
                'id' => "ID $i",
                'name' => "Name $i",
            ];
        }

        $result = Category::insert($categories);
        self::assertTrue($result);

        $count = Category::count();
        self::assertEquals(10, $count);
    }

    public function testFind()
    {
        $this->seed(CategorySeeder::class);

        $category = Category::find('FOOD');

        self::assertNotNull($category);
        assertEquals('FOOD', $category->id);
        assertEquals('Food', $category->name);
        assertEquals('Food Category', $category->description);
    }

    public function testUpdate()
    {
        $this->seed(CategorySeeder::class);
        $category = Category::find('FOOD');
        $category->name = 'Food Updated';
        $result = $category->update();
        self::assertTrue($result);
    }

    public function testSelect()
    {
        for ($i = 0; $i < 5; $i++) {
            $category = new Category();
            $category->id = "ID $i";
            $category->name = "Name $i";
            $category->save();
        }

        $categories = Category::whereNull('description')->get();

        assertEquals(5, $categories->count());

        $categories->each(function (Category $category) {
            self::assertNull($category->description);
            $category->description = 'Updated';
            $category->update();
        });
    }

    public function testUpdateMany()
    {
        $categories = [];
        for ($i = 0; $i < 10; $i++) {
            $categories[] = [
                'id' => "ID $i",
                'name' => "Name $i",
            ];
        }

        $result = Category::insert($categories);
        assertTrue($result);

        Category::whereNull('description')->update([
            'description' => 'Updated',
        ]);

        $total = Category::where('description', 'Updated')->count();
        assertEquals(10, $total);
    }

    public function testDelete()
    {
        $this->seed(CategorySeeder::class);
        $category = Category::find('FOOD');
        $result = $category->delete();
        assertTrue($result);

        $total = Category::count();
        assertEquals(0, $total);
    }

    public function testDeleteMany()
    {
        $categories = [];
        for ($i = 0; $i < 10; $i++) {
            $categories[] = [
                'id' => "ID $i",
                'name' => "Name $i",
            ];
        }

        $result = Category::insert($categories);
        assertTrue($result);

        $total = Category::count();
        assertEquals(10, $total);

        Category::whereNull('description')->delete();

        $total = Category::count();
        assertEquals(0, $total);
    }

    public function testCreate()
    {
        $request = [
            'id' => 'FOOD',
            'name' => 'Food',
            'description' => 'Food Updated',
        ];

        $category = Category::create($request);
        assertNotNull($category->description);
    }

    public function testUpdateMass()
    {
        $this->seed(CategorySeeder::class);

        $request = [
            'name' => 'Food Updated',
            'description' => 'Food Category Updated',
        ];

        $category = Category::find('FOOD');
        $category->fill($request);
        $category->save();

        assertNotNull($category->description);

    }

    public function testGlobalActiveScope()
    {
        $category = new Category();
        $category->id = "FOOD";
        $category->name = "Food";
        $category->description = "Food Updated";
        $category->is_active = false;
        $category->save();

        $category = Category::find('FOOD');
        self::assertNull($category);

        $category = Category::withoutGlobalScopes([IsActiveScope::class])->find('FOOD');
        self::assertNotNull($category);
    }

    public function testOneToMany()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $category = Category::find('FOOD');
        assertNotNull($category);

        $product = $category->products;
        assertNotNull($product);
        self::assertCount(1, $product);
    }
}
