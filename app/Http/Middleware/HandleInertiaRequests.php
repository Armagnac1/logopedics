<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     */
    public function share(Request $request): array
    {
        $permissions = [];
        $roles = [];
        if (auth()->user()) {
            $user = auth()->user();
            $cacheKey = 'user_' . $user->id . '_roles_permissions';
            
            $cachedData = cache()->remember($cacheKey, now()->addHours(24), function () use ($user) {
                return [
                    'permissions' => $user->roles()->with('permissions')->get()->pluck('permissions')->first()->pluck('name'),
                    'roles' => $user->roles()->get()->pluck('name')
                ];
            });
            
            $permissions = $cachedData['permissions'];
            $roles = $cachedData['roles'];
        }
        $urlPrev = request()->wantsJson() ? null : $this->savePreviousUrl();

        return array_merge(parent::share($request), [
            'roles' => $roles,
            'permissions' => $permissions,
            'urlPrev' => fn () => $urlPrev,
            'impersonate' => fn () => $request->session()->get('impersonate'),
            'flash' => fn () => $request->session()->get('flash'),
        ]);
    }

    protected function savePreviousUrl()
    {
        $history = session()->get('linkHistory', []);
        $previous = end($history) ?: null;
        $lastBeforePrevious = prev($history) ?: null;
        $current = url()->current();
        if ($lastBeforePrevious === $current) {
            array_pop($history);
            end($history);
            $previous = prev($history) ?: null;
        } else {
            if ($current !== route('login') && $current !== '') {
                $history[] = $current;
            }
            if (count($history) > 10) {
                array_shift($history);
            }
        }
        session()->put('linkHistory', $history);

        return $previous;
    }
}
