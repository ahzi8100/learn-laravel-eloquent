<?php

namespace Tests\Feature;

use App\Models\Voucher;
use Database\Seeders\VoucherSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use function PHPUnit\Framework\assertEquals;

class VoucherTest extends TestCase
{
    public function testVoucher()
    {
        $voucher = new Voucher();
        $voucher->name = 'Sample Voucher';
        $voucher->voucher_code = '123314234234';
        $voucher->save();

        self::assertNotNull($voucher->id);
    }

    public function testVoucherUUID()
    {
        $voucher = new Voucher();
        $voucher->name = 'Sample Voucher';
        $voucher->save();

        self::assertNotNull($voucher->id);
        self::assertNotNull($voucher->voucher_code);
    }

    public function testSoftDelete()
    {
        $this->seed(VoucherSeeder::class);
        $voucher = Voucher::where('name', 'Sample Voucher')->first();
        $voucher->delete();

        $voucher = Voucher::where('name', 'Sample Voucher')->first();
        self::assertNull($voucher);

        $voucher = Voucher::withTrashed()->where('name', 'Sample Voucher')->first();
        self::assertNotNull($voucher);
    }

    public function testLocalScope()
    {
        $voucher = new Voucher();
        $voucher->name = 'Sample Voucher';
        $voucher->is_active = true;
        $voucher->save();

        $total = Voucher::active()->count();
        assertEquals(1, $total);

        $total = Voucher::nonActive()->count();
        assertEquals(0, $total);


    }
}
