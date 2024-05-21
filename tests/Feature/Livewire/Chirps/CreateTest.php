<?php

namespace Tests\Feature\Livewire\Chirps;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_render(): void
    {

        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/chirps');

        $response
            ->assertOk()
            ->assertSeeVolt('chirps.create');
    }

    public function test_chirp_can_be_created(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $component = Volt::test('chirps.create')
            ->set('message', 'Hello I\'m a test chirp')
            ->call('store');

        $component
            ->assertHasNoErrors()
            ->assertNoRedirect();

        $user->refresh();

        $this->assertSame(1, $user->loadCount('chirps')->chirps_count);
        $this->assertSame('Hello I\'m a test chirp',
            $user->chirps()->get()->first()->message);
    }
}