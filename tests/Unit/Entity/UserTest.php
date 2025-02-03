<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    public function testEachUserHasAtLeastUserRole()
    {
        $user = new User();

        $this->assertContains('ROLE_USER', $user->getRoles(), "Every user must have ROLE_USER by default.");
    }

    public function testAddRoleAddsNewRole(): void
    {
        $user = new User();

        $user->addRole('ROLE_ADMIN');

        $this->assertContains("ROLE_ADMIN", $user->getRoles(), "User should have ROLE_ADMIN after adding ROLE_ADMIn role");
    }

    public function testRemoveRoleRemovesExistingRole(): void
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $user->removeRole('ROLE_ADMIN');

        $this->assertNotContains('ROLE_ADMIN', $user->getRoles(), "ROLE_ADMIN should be removed.");
        $this->assertContains('ROLE_USER', $user->getRoles(), "ROLE_USER should always remain.");
    }
   

}