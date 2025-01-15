<?php

namespace Khafidprayoga\PhpMicrosite\UseCases;

use Khafidprayoga\PhpMicrosite\Models\DTO\UserDTO;
use Khafidprayoga\PhpMicrosite\Models\Entities\User;
use Khafidprayoga\PhpMicrosite\Services\UserService;

class UserServiceImpl extends Init implements UserService
{
    public function createUser(UserDTO $request): User
    {
        // TODO: Implement createUser() method.
    }

    public function getUserById(int $userId): User
    {
        // TODO: Implement getUserById() method.
    }

    public function getUserByUsername(string $username): User
    {
        // TODO: Implement getUserByUsername() method.
    }

    public function getPosts(int $userId): ?array
    {
        // TODO: Implement getPosts() method.
    }

    public function getLikedPosts(int $userId): ?array
    {
        // TODO: Implement getLikedPosts() method.
    }
}
