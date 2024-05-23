<?php

namespace Tests\Feature\Livewire\Chirps;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class ListTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    public function test_it_can_render(): void
    {
        $component = Volt::test('chirps.list');

        $component->assertSee('');
    }

    public function test_chirps_increses_when_event_is_dispatched(): void
    {
        $component = Volt::test('chirps.list');
        $this->assertCount(0, $component->chirps);

        Volt::test('chirps.create')
            ->set('message', 'hello world')
            ->call('store')
            ->assertDispatched('chirp-created');

        $component->dispatch('chirp-created');
        $this->assertCount(1, $component->chirps);
    }
}
