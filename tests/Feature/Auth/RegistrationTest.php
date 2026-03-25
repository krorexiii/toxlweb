<?php

test('registration screen is not available publicly', function () {
    $this->get('/register')->assertNotFound();
});

test('public users cannot submit registration requests', function () {
    $this->post('/register', [
        'name' => 'John Doe',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertNotFound();

    $this->assertGuest();
});