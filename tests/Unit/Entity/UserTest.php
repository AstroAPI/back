<?php

namespace App\Tests\Unit\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testCreateUser(): void
    {
        $user = new User();
        $this->assertInstanceOf(User::class, $user);
    }

    public function testEmailGetterAndSetter(): void
    {
        $user = new User();
        $email = 'test@example.com';
        
        $user->setEmail($email);
        $this->assertEquals($email, $user->getEmail());
    }

    public function testRolesGetterAndSetter(): void
    {
        $user = new User();
        $roles = ['ROLE_ADMIN'];
        
        $user->setRoles($roles);
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
        $this->assertContains('ROLE_USER', $user->getRoles()); // ROLE_USER is always included
    }

    public function testPasswordGetterAndSetter(): void
    {
        $user = new User();
        $password = 'password123';
        
        $user->setPassword($password);
        $this->assertEquals($password, $user->getPassword());
    }

    public function testCityGetterAndSetter(): void
    {
        $user = new User();
        $city = 'Paris';
        
        $user->setCity($city);
        $this->assertEquals($city, $user->getCity());
    }

    public function testUserIdentifier(): void
    {
        $user = new User();
        $email = 'test@example.com';
        
        $user->setEmail($email);
        $this->assertEquals($email, $user->getUserIdentifier());
    }
}