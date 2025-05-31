<?php

namespace App\Repositories\Cached;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

class CachedUserRepository implements UserRepositoryInterface
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected CacheRepository $cache
    ) {}

    public function getForIndex($perPage = 20)
    {
        $cacheKey = 'users.index.' . $perPage;

        return $this->cache->remember($cacheKey, now()->addMinutes(1), function () use ($perPage) {
            return $this->userRepository->getForIndex($perPage);
        });
    }

    public function getTutorId($userId): ?int
    {
        $cacheKey = 'users.tutor_id.' . $userId;
        return $this->cache->rememberForever($cacheKey, function () use ($userId) {
            return $this->userRepository->getTutorId($userId);
        });
    }
}
