<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Wallet;
use Cassandra\Custom;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\ImageSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\VirtualAccountSeeder;
use Database\Seeders\WalletSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;

class CustomerTest extends TestCase
{
    public function testOneToOne()
    {
        $this->seed(customerSeeder::class);
        $this->seed(walletSeeder::class);

        $customer = Customer::find('AHZI');
        self::assertNotNull($customer);

        $wallet = $customer->wallet;
        self::assertNotNull($wallet);

        self::assertEquals(1000000000000, $wallet->amount);
    }

    public function testOneToOneQuery()
    {
        $customer = new Customer();
        $customer->id = 'AHZI';
        $customer->name = 'Ahzi';
        $customer->email = 'ahzi@gmail.com';
        $customer->save();

        $wallet = new Wallet();
        $wallet->amount = 1000000;
        $customer->wallet()->save($wallet);

        assertNotNull($wallet->customer_id);
    }

    public function testHasOneThrough()
    {
        $this->seed([customerSeeder::class, walletSeeder::class, virtualAccountSeeder::class]);

        $cutomer  = Customer::find('AHZI');
        assertNotNull($cutomer);

        $virutalAccount = $cutomer->virtualAccount;
        assertNotNull($virutalAccount);
        assertEquals('BCA', $virutalAccount->bank);
    }

    public function testManyToMany()
    {
        $this->seed([CustomerSeeder::class, CategorySeeder::class, ProductSeeder::class]);

        $customer = Customer::find('AHZI');
        assertNotNull($customer);

        $customer->likeProducts()->attach(1);

        $products = $customer->likeProducts;
        self::assertCount(1, $products);
        assertEquals(1, $products[0]->id);
    }

    public function testManyToManyDetach()
    {
        $this->testManyToMany();

        $customer = Customer::find('AHZI');
        assertNotNull($customer);

        $customer->likeProducts()->detach('1');
        $products = $customer->likeProducts;
        self::assertCount(0, $products);
    }

    public function testPivotAttribute()
    {
        $this->testManyToMany();

        $customer = Customer::find('AHZI');
        assertNotNull($customer);

        $products = $customer->likeProducts;
        foreach ($products as $product) {
            $pivot = $product->pivot;
            assertNotNull($pivot->customer_id);
            assertNotNull($pivot->product_id);
            assertNotNull($pivot->created_at);
        }
    }

    public function testPivotAttributeCondition()
    {
        $this->testManyToMany();

        $customer = Customer::find('AHZI');
        assertNotNull($customer);

        $products = $customer->likeProductsLastWeek;
        foreach ($products as $product) {
            $pivot = $product->pivot;
            assertNotNull($pivot->customer_id);
            assertNotNull($pivot->product_id);
            assertNotNull($pivot->created_at);
        }
    }

    public function testPivotModel()
    {
        $this->testManyToMany();

        $customer = Customer::find('AHZI');
        assertNotNull($customer);

        $products = $customer->likeProducts;
        foreach ($products as $product) {
            $pivot = $product->pivot;
            assertNotNull($pivot->customer_id);
            assertNotNull($pivot->product_id);
            assertNotNull($pivot->created_at);
            assertNotNull($pivot->customer);
            assertNotNull($pivot->product);
        }
    }

    public function testOneToOnePolymorphic()
    {
        $this->seed([CustomerSeeder::class, ImageSeeder::class]);

        $customer = Customer::find('AHZI');
        assertNotNull($customer);

        $image = $customer->image;
        assertNotNull($image);
        assertEquals('image1.com', $image->url);
    }

    public function testEager()
    {
        $this->seed([CustomerSeeder::class, WalletSeeder::class, ImageSeeder::class]);

        $customer = Customer::with(['wallet', 'image'])->find('AHZI');
        assertNotNull($customer);

    }

    public function testEagerModel()
    {
        $this->seed([CustomerSeeder::class, WalletSeeder::class, ImageSeeder::class]);

        $customer = Customer::find('AHZI');
        assertNotNull($customer);

    }
}
