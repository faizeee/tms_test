<?php

namespace Database\Factories;

use App\Models\Content;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Content>
 */
class ContentFactory extends Factory
{
    protected $model = Content::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    
    public function definition(): array
    {
        return [
            "key"=>"created.key.from.factory.".uniqid(),
            "content"=> $this->faker->sentence(6)
        ];
    }

    public function withTranslations($locales = [1,2,3])
    {
        return $this->afterCreating(function (Content $content) use ($locales) {
            foreach ($locales as $locale) {
                $content->translations()->create([
                    'locale_id' => $locale,
                    'translation' => $this->faker->sentence(3),
                ]);
            }
        });
    }

    public function withTags($tags = [1,2,3])
    {
        return $this->afterCreating(function (Content $content) use ($tags) {
            $content->tags()->sync($tags);
        });
    }
}
