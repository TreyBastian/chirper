<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('it can render', function () {
    $component = Volt::test('chirps.list');

    $component->assertSee('');
});

test('chirps increses when event is dispatched', function () {
    $component = Volt::test('chirps.list');
    expect($component->chirps)->toHaveCount(0);

    Volt::test('chirps.create')
        ->set('message', 'hello world')
        ->call('store')
        ->assertDispatched('chirp-created');

    $component->dispatch('chirp-created');
    expect($component->chirps)->toHaveCount(1);
});
