<?php

namespace Tests\Feature\Livewire\Chirps;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
});

test('it can render', function () {
    $response = get('/chirps');

    $response->assertOk()->assertSeeVolt('chirps.create');
});

test('chirp can be created', function () {
    $component = Volt::test('chirps.create')->set('message', 'Hello I\'m a test chirp')->call('store');

    $component->assertHasNoErrors()->assertNoRedirect()->assertDispatched('chirp-created');

    $this->user->refresh();

    expect($this->user->loadCount('chirps')->chirps_count)->toBe(1);
    expect($this->user->chirps()->get()->first()->message)->toBe('Hello I\'m a test chirp');
});

test('invaid chirp cannot be submitted', function () {
    $component = Volt::test('chirps.create')->set('message', '')->call('store');
    $component->assertHasErrors()->assertNotDispatched('chirp-created');

    $component = Volt::test('chirps.create')->set('message', str_repeat('abcdefgh', 32))->call('store');
    $component->assertHasErrors()->assertNotDispatched('chirp-created');

    $component = Volt::test('chirps.create')->set('message', str_repeat('abcde', 51))->call('store');
    $component->assertHasNoErrors();
});
