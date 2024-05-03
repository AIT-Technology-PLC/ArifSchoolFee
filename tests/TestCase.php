<?php

namespace Tests;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected $user;

    protected Company $company;

    protected $seed = true;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = Company::first();

        $this->user = User::firstWhere('is_admin', 0);

        $this->user->syncRoles(['Custom']);

        $this->actingAs($this->user);
    }
}
