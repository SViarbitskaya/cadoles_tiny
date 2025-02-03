<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Group;
use PHPUnit\Framework\TestCase;

class GroupTest extends TestCase
{
    public function testAddUserAddsUserToGroup():void
    {
        $group = new Group();
        $user = new User();

        $group->addUser($user);
        $this->assertTrue($group->getUsers()->contains($user), "User should be in the group after addUser()");
        $this->assertTrue($user->getGroups()->contains($group), "Group should be in user's groups after addUser()");

    }

    public function testRemoveUserRemovesUserFromGroup(): void
    {
        $group = new Group();
        $user = new User();

        $group->addUser($user);
        $group->removeUser($user);


        $this->assertFalse($group->getUsers()->contains($user), "User should not be in the group after removeUser()");
        $this->assertFalse($user->getGroups()->contains($group), "Group should not be in user's groups after removeUser()");
    }

    public function testRemovingUserNotInGroupDoesNothing(): void
    {
        $group = new Group();
        $user = new User();

        $initialUserCount = $group->getUsers()->count();
        $group->removeUser($user);
        
        $this->assertSame($initialUserCount, $group->getUsers()->count(), "Removing a user that was never added should not modify the group");
        $this->assertFalse($user->getGroups()->contains($group), "User should not have the group in their groups list if never added");
    }


}