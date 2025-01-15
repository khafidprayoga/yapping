<?php

namespace Khafidprayoga\PhpMicrosite\Services;

use Khafidprayoga\PhpMicrosite\Models\DTO\UserDTO;
use Khafidprayoga\PhpMicrosite\Models\Entities\User;

interface UserService
{
    public function createUser(UserDTO $request): User;

    public function getUserById(int $userId): User;

    public function getUserByUsername(string $username): User;

    public function getPosts(int $userId): ?array;

    public function getLikedPosts(int $userId): ?array;
}