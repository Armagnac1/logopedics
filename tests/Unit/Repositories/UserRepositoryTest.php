<?php

namespace Tests\Unit\Repositories;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = new UserRepository();
    }

    public function test_get_for_index_returns_paginated_users_with_roles()
    {
        $users = User::limit(5)->get();

        // Act
        $result = $this->userRepository->getForIndex(10); // perPage = 10

        // Assert
        $this->assertCount(5, $result->items());
        $this->assertEquals(10, $result->perPage());
        $this->assertEquals($users->first()->id, $result->items()[0]->id);
        $this->assertTrue($result->items()[0]->relationLoaded('roles'));
    }
}
