<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(Tag::count() > 0){
            return;
        }
        
        $tags = [
            ['name' => 'mobile', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'desktop', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'web', 'created_at' => now(), 'updated_at' => now()],
        ];

        Tag::insert($tags);
    }
}
