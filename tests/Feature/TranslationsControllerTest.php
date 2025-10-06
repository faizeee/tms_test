<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\Locale;
use App\Models\Tag;
use App\Models\User;
use Database\Seeders\LocaleSeeder;
use Database\Seeders\TagSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TranslationsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(LocaleSeeder::class);
        $this->seed(TagSeeder::class);
        // Seed sample data
        $this->locales = Locale::all();
        $this->tags = Tag::all();

        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user,["*"]);
    }

    /** @test */
    public function it_can_create_a_translation_set()
    {
        $payload = [
            'key' => 'auth.login',
            'content' => 'Login label',
            'tags' => [$this->tags[0]->id],
            'translations' => [
                ['locale_id' => $this->locales[0]->id, 'translation' => 'Login'],
                ['locale_id' => $this->locales[1]->id, 'translation' => 'Connexion'],
            ],
        ];

        $response = $this->postJson('/api/translations', $payload);

        $response->assertCreated()
                 ->assertJsonStructure(structure: [
                     'data' => [
                         'id',
                         'key',
                         'translations' => [['id', 'locale', 'translation']],
                         'tags' => [],
                     ],
                     "message",
                 ]);

        $this->assertDatabaseHas('contents', ['key' => 'auth.login']);
        $this->assertDatabaseCount('content_translations', 2);
    }

    /** @test */
    public function it_can_update_a_translation_set()
    {
        $content = Content::factory()
            ->withTranslations($this->locales->pluck('id')->toArray())
            ->withTags($this->tags->pluck('id')->toArray())
            ->create();

        $payload = [
            'key' => $content->key,
            'content' => 'Updated',
            'tags' => [$this->tags[1]->id],
            'translations' => [
                ['locale_id' => $this->locales[0]->id, 'translation' => 'Updated login'],
            ],
        ];

        $response = $this->putJson("/api/translations/{$content->id}", $payload);

        $response->assertOk()
                 ->assertJsonFragment(['message' => 'Translation updated successfully.']);
    }

    /** @test */
    public function it_can_export_translations_by_tag_and_locale()
    {
         $content = Content::factory()
            ->withTranslations($this->locales->pluck('id')->toArray())
            ->withTags($this->tags->pluck('id')->toArray())
            ->create();
            
        $tag = $content->tags->first();
        $locale = $content->translations->first()->locale->code;

        $response = $this->getJson("/api/translations/export/{$tag->name}/{$locale}");

        $response->assertOk()
                 ->assertJsonStructure([
                     $content->key
                 ]);
    }

    /** @test */
    public function it_can_list_translations_with_filters()
    {
        Content::factory()
            ->withTranslations($this->locales->pluck('id')->toArray())
            ->withTags($this->tags->pluck('id')->toArray())
            ->count(5)
            ->create();
        $response = $this->getJson('/api/translations?key=auth');

        $response->assertOk()->assertJsonStructure(['data']);
    }
}
