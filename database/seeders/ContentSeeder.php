<?php

namespace Database\Seeders;

use App\Models\Content;
use App\Models\ContentTag;
use App\Models\ContentTranslation;
use App\Models\Locale;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\LazyCollection;
use Str;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locales = Locale::all();
        $tags = Tag::all();
        $total_contents = 33_333;// ~33k
        $batch_size = 1000;

        LazyCollection::times($total_contents)
        ->chunk($batch_size)
        ->each(function($chunk) use ($locales,$tags){
            $contents = [];
            $now = now();
            $keyPrefixes = ['dashboard', 'auth', 'profile', 'settings', 'system', 'user'];
            $actions = ['save', 'delete', 'update', 'cancel', 'view', 'edit'];
            foreach($chunk as $_){
                $key = fake()->randomElement($keyPrefixes) . '.' . fake()->randomElement($actions);
                $contents[] = [
                    'key' => $key.".".uniqid(),
                    'content' => fake()->unique()->sentence(nbWords: 3),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            Content::insert($contents);
            $content_ids = Content::latest()->limit(count($contents))->pluck('id');
            $content_translations = [];
            $content_tags = [];
            foreach($content_ids as $content_id){
                foreach($locales as $locale){
                    $content_translations[] = [
                        'content_id' => $content_id,
                        'locale_id' => $locale->id,
                        'translation' => fake()->sentence(3),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
                $randomTags = $tags->random(rand(1, max: 3));
                foreach ($randomTags as $tag) {
                        $content_tags[] = [
                            'content_id' => $content_id,
                            'tag_id' => $tag->id,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
            }

            ContentTranslation::insert($content_translations);
            ContentTag::insert($content_tags);
            echo "Inserted batch of " . count($chunk) . " contents with translations and tags" . PHP_EOL;
        });
    }
}
