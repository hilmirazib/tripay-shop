<?php

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(fn() => $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class));


beforeEach(function () {
    // pastikan roles ada untuk guard 'web'
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);
});

it('redirects guest to login when visiting admin', function () {
    $this->get('/admin')->assertRedirect('/login');
});

it('forbids non-admin user from visiting admin', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->get('/admin')->assertStatus(403);
});

it('allows admin to visit admin dashboard', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin'); // guard default 'web'

    $this->actingAs($admin)->get('/admin')->assertOk();
    // kalau mau tambahkan assert lain setelah page admin jadi
});
