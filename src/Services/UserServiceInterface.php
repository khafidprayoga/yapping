<?php

namespace Khafidprayoga\PhpMicrosite\Services;

use Khafidprayoga\PhpMicrosite\Models\DTO\UserDTO;
use Khafidprayoga\PhpMicrosite\Models\Entities\User;

interface UserServiceInterface
{
    public function createUser(UserDTO $request): array;

    public function getUserById(int $userId): array;

    public function getUserByUsername(string $username): array;

    public function getPosts(int $userId): ?array;

    public function getLikedPosts(int $userId): ?array;

}
