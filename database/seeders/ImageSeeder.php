<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        {
            $image = new Image();
            $image->url = 'image1.com';
            $image->imageable_id = 'AHZI';
            $image->imageable_type = 'customer';
            $image->save();
        }
        {
            $image2 = new Image();
            $image2->url = 'image2.com';
            $image2->imageable_id = '1';
            $image2->imageable_type = 'product';
            $image2->save();
        }
    }
}
