<?php

namespace Database\Seeders;

use App\Models\VirtualAccount;
use App\Models\Wallet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VirtualAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wallet  = Wallet::where('customer_id', 'AHZI')->firstOrFail();

        $virtualAccount = new VirtualAccount();
        $virtualAccount->bank = 'BCA';
        $virtualAccount->va_number = '12334232';
        $virtualAccount->wallet_id = $wallet->id;
        $virtualAccount->save();
    }
}
