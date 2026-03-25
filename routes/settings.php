<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::livewire('settings/profile', 'pages::settings.profile')->name('profile.edit');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('settings/appearance', 'pages::settings.appearance')->name('appearance.edit');

    Route::livewire('settings/security', 'pages::settings.security')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('security.edit');

    Route::livewire('admin/site-settings', 'pages::admin.site-settings')->name('admin.site-settings');
    Route::livewire('admin/languages', 'pages::admin.languages')->name('admin.languages');
    Route::livewire('admin/products', 'pages::admin.products')->name('admin.products');
    Route::livewire('admin/works', 'pages::admin.works')->name('admin.works');
});
