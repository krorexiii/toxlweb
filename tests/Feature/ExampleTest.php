<?php

use Database\Seeders\HomepageSeeder;

test('returns a successful response', function () {
    $this->seed(HomepageSeeder::class);

    $response = $this->get(route('home'));

    $response->assertOk();
});