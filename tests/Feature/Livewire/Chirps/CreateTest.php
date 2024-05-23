<?php

namespace Tests\Feature\Livewire\Chirps;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_it_can_render(): void
    {

        $response = $this->get('/chirps');

        $response
            ->assertOk()
            ->assertSeeVolt('chirps.create');
    }

    public function test_chirp_can_be_created(): void
    {

        $component = Volt::test('chirps.create')
            ->set('message', 'Hello I\'m a test chirp')
            ->call('store');

        $component
            ->assertHasNoErrors()
            ->assertNoRedirect()
            ->assertDispatched('chirp-created');

        $this->user->refresh();

        $this->assertSame(1, $this->user->loadCount('chirps')->chirps_count);
        $this->assertSame('Hello I\'m a test chirp',
            $this->user->chirps()->get()->first()->message);
    }

    public function test_invaid_chirp_cannot_be_submitted(): void
    {
        $component = Volt::test('chirps.create')->set('message', '')
            ->call('store');
        $component->assertHasErrors()->assertNotDispatched('chirp-created');

        $component = Volt::test('chirps.create')->set('message', str_repeat('abcdefgh', 32))
            ->call('store');
        $component->assertHasErrors()->assertNotDispatched('chirp-created');

        $component = Volt::test('chirps.create')->set('message', str_repeat('abcde', 51))
            ->call('store');
        $component->assertHasNoErrors();

    }
}
