<?php

namespace App\Providers\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Support\Facades\Cache;

class CachedUserProvider extends EloquentUserProvider
{
    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        $cacheKey = 'user_' . $identifier;
        
        return Cache::remember($cacheKey, now()->addMinutes(60), function () use ($identifier) {
            $user = parent::retrieveById($identifier);
            if ($user) {
                $this->cacheUserPermissions($user);
            }
            return $user;
        });
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed  $identifier
     * @param  string  $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        $cacheKey = 'user_token_' . $identifier . '_' . $token;
        
        return Cache::remember($cacheKey, now()->addMinutes(60), function () use ($identifier, $token) {
            $user = parent::retrieveByToken($identifier, $token);
            if ($user) {
                $this->cacheUserPermissions($user);
            }
            return $user;
        });
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string  $token
     * @return void
     */
    public function updateRememberToken(UserContract $user, $token)
    {
        parent::updateRememberToken($user, $token);
        
        // Clear the user's cache when their remember token is updated
        $this->clearUserCache($user);
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {
        $valid = parent::validateCredentials($user, $credentials);
        
        if ($valid) {
            // Clear the user's cache when credentials are validated
            $this->clearUserCache($user);
        }
        
        return $valid;
    }

    /**
     * Cache user permissions
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    protected function cacheUserPermissions(UserContract $user)
    {
        if (method_exists($user, 'getAllPermissions')) {
            $permissionsCacheKey = 'user_permissions_' . $user->getAuthIdentifier();
            Cache::remember($permissionsCacheKey, now()->addMinutes(60), function () use ($user) {
                return $user->getAllPermissions()->pluck('name')->toArray();
            });
        }
    }

    /**
     * Clear all user related cache
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    protected function clearUserCache(UserContract $user)
    {
        $userId = $user->getAuthIdentifier();
        Cache::forget('user_' . $userId);
        Cache::forget('user_permissions_' . $userId);
    }
}
