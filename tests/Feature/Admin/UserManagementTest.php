<?php

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\UserResource;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

it('renders the user resource pages for any authenticated user', function () {
    $this->actingAs(User::factory()->create());

    $this->get(UserResource::getUrl('index'))->assertOk();
    $this->get(UserResource::getUrl('create'))->assertOk();
    $this->get(UserResource::getUrl('edit', ['record' => User::factory()->create()]))->assertOk();
});

it('creates a verified user through the admin form', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Guru Baru',
            'email' => 'guru@example.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $user = User::where('email', 'guru@example.com')->first();

    expect($user)->not->toBeNull()
        ->and(Hash::check('rahasia123', $user->password))->toBeTrue()
        ->and($user->email_verified_at)->not->toBeNull();
});

it('rejects a mismatched password confirmation', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Guru Baru',
            'email' => 'guru@example.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'berbeda123',
        ])
        ->call('create')
        ->assertHasFormErrors(['password']);
});

it('logs an activity entry when a user is created', function () {
    $this->actingAs(User::factory()->create());

    $user = User::factory()->create(['email' => 'jejak@example.com']);

    expect(ActivityLog::query()
        ->where('action', 'user.created')
        ->where('subject_id', $user->id)
        ->exists())->toBeTrue();
});

it('hides the delete action for the signed in user only', function () {
    $admin = User::factory()->create();
    $other = User::factory()->create();

    $this->actingAs($admin);

    Livewire::test(ListUsers::class)
        ->assertTableActionHidden('delete', $admin)
        ->assertTableActionVisible('delete', $other);
});
