<?php

namespace App\Tests\Unit\Controller;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityControllerTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
    }

    public function testUserPasswordHashing(): void
    {
        $container = static::getContainer();
        
        // Get the password hasher service
        $passwordHasher = $container->get(UserPasswordHasherInterface::class);
        
        // Create a user
        $user = new User();
        $plainPassword = 'password123';
        
        // Hash the password
        $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
        
        // Check that the password is hashed (not the same as plain password)
        $this->assertNotEquals($plainPassword, $hashedPassword);
        
        // Verify that the hasher validates this password
        $this->assertTrue($passwordHasher->isPasswordValid($user, $plainPassword));
    }
}