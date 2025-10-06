<?php

namespace Tests\Feature;

use App\Models\Tag;
use App\Models\User;
use Database\Seeders\TagSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TagsControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_all_tags()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $this->seed(TagSeeder::class);

        $response = $this->getJson('/api/tags');

        $response->assertOk()
                 ->assertJsonStructure([['id', 'name']]);
    }
}
