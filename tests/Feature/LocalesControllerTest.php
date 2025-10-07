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

    protected function setUp(): void {
        parent::setUp();
        $user = User::factory()->create();
        Sanctum::actingAs($user,["*"]);
        $this->seed(LocaleSeeder::class);
    }

    /** @test */
    public function it_returns_all_locales()
    {
        $response = $this->getJson('/api/locales');
        $response->assertOk()
                 ->assertJsonStructure([['id', 'code', 'name']]);
    }

    /** @test */
    public function it_creates_a_locale(){
        $payload = [
            "name"=> "Urdu",
            "code" => "ur"
        ];

        $response = $this->postJson("/api/locales",$payload);
        $response->assertOk()->assertJsonStructure(["id","code","name"]);
        $this->assertDatabaseHas("locales",["code"=>$payload["code"]]);
        $this->assertDatabaseCount("locales",4);
    }
    
}
