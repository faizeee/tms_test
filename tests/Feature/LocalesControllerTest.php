<?php

namespace Tests\Feature;

use App\Models\Locale;
use App\Models\User;
use Database\Seeders\LocaleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LocalesControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_all_locales()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $this->seed(LocaleSeeder::class);

        $response = $this->getJson('/api/locales');

        $response->assertOk()
                 ->assertJsonStructure([['id', 'code', 'name']]);
    }
}
