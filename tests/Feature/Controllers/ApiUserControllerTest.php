<?php

declare(strict_types=1);

use App\Models\Team;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Testing\Fluent\AssertableJson;

beforeEach(function (): void {
    // Create admin user with team
    $this->admin = User::factory()->withPersonalTeam()->create();
    $this->admin->currentTeam->users()->attach(
        $this->admin,
        ['role' => 'admin']
    );

    // Create regular user with write permissions
    $this->writer = User::factory()->create();
    $this->admin->currentTeam->users()->attach(
        $this->writer,
        ['role' => 'editor']
    );
    $this->writer->switchTeam($this->admin->currentTeam);

    // Create regular user with only read permissions
    $this->reader = User::factory()->create();
    $this->admin->currentTeam->users()->attach(
        $this->reader,
        ['role' => 'reader']
    );
    $this->reader->switchTeam($this->admin->currentTeam);
});

test('unauthenticated users cannot access api endpoints', function (): void {
    $response = $this->getJson('/api/user');
    $response->assertUnauthorized();
});

test('admin can list all users', function (): void {
    Sanctum::actingAs($this->admin, ['*']);

    $response = $this->getJson('/api/user');

    $response->assertOk()
        ->assertJson(
            fn (AssertableJson $json): AssertableJson => $json->has(3) // admin + writer + reader
                ->first(
                    fn ($json) => $json->where('id', $this->admin->id)
                        ->where('name', $this->admin->name)
                        ->where('email', $this->admin->email)
                        ->etc()
                )
        );
});

test('admin can create new user', function (): void {
    Sanctum::actingAs($this->admin, ['create']);

    $userData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $response = $this->postJson('/api/user', $userData);

    $response->assertCreated()
        ->assertJson(
            fn (AssertableJson $json): AssertableJson => $json->where('name', $userData['name'])
                ->where('email', $userData['email'])
                ->etc()
        );
});

test('admin can view specific user', function (): void {
    Sanctum::actingAs($this->admin, ['read']);

    $response = $this->getJson("/api/user/{$this->writer->id}");

    $response->assertOk()
        ->assertJson(
            fn (AssertableJson $json): AssertableJson => $json->where('id', $this->writer->id)
                ->where('name', $this->writer->name)
                ->where('email', $this->writer->email)
                ->etc()
        );
});

test('admin can update user', function (): void {
    Sanctum::actingAs($this->admin, ['update']);

    $updateData = [
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
    ];

    $response = $this->putJson("/api/user/{$this->writer->id}", $updateData);

    $response->assertOk()
        ->assertJson(
            fn (AssertableJson $json): AssertableJson => $json->where('name', $updateData['name'])
                ->where('email', $updateData['email'])
                ->etc()
        );
});

test('admin can delete user', function (): void {
    Sanctum::actingAs($this->admin, ['delete']);

    $response = $this->deleteJson("/api/user/{$this->writer->id}");

    $response->assertNoContent();
    $this->assertDatabaseMissing('users', ['id' => $this->writer->id]);
});

test('users can view their own profile', function (): void {
    Sanctum::actingAs($this->writer, ['read']);

    $response = $this->getJson("/api/user/{$this->writer->id}");

    $response->assertOk();
});

test('users cannot view other users profiles without permission', function (): void {
    Sanctum::actingAs($this->reader, ['read']);

    $response = $this->getJson("/api/user/{$this->writer->id}");

    $response->assertForbidden();
});

test('users cannot delete themselves', function (): void {
    Sanctum::actingAs($this->writer, ['delete']);

    $response = $this->deleteJson("/api/user/{$this->writer->id}");

    $response->assertForbidden();
    $this->assertDatabaseHas('users', ['id' => $this->writer->id]);
});

test('token abilities are properly checked', function (): void {
    // Token with only read ability
    Sanctum::actingAs($this->admin, ['read']);
    $response = $this->postJson('/api/user', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);
    $response->assertForbidden();

    // Token with create ability
    Sanctum::actingAs($this->admin, ['create']);
    $response = $this->postJson('/api/user', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);
    $response->assertCreated();
});
