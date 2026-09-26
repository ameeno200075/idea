<?php

use App\Models\User;

test('register a user', function () {

    visit('/register')
        ->fill('name', 'John Doe')
        ->fill('email', 'john-'.uniqid().'@example.com')
        ->fill('password', 'password123')
        ->press('Create Account')
        ->assertPathIs('/ideas');

    $this->assertAuthenticated();
});

test('user can login', function () {

    $user = User::factory()->create([
        'email' => 'john@example.com',
        'password' => 'password123',
    ]);

    visit('/login')
        ->fill('email', 'john@example.com')
        ->fill('password', 'password123')
        ->press('Login')
        ->assertPathIs('/ideas');

    $this->assertAuthenticatedAs($user);
});
