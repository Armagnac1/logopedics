<?php

namespace App\Providers;

use App\Repositories\Cached\CachedTagRepository;
use App\Repositories\Contracts\CalendarRepositoryInterface;
use App\Repositories\Contracts\LearningMaterialRepositoryInterface;
use App\Repositories\Contracts\LessonRepositoryInterface;
use App\Repositories\Contracts\PupilRepositoryInterface;
use App\Repositories\Contracts\TagRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\CalendarRepository;
use App\Repositories\Eloquent\LearningMaterialRepository;
use App\Repositories\Eloquent\LessonRepository;
use App\Repositories\Eloquent\PupilRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Services\CrossDomain\Ai\AiProviderFactory;
use App\Services\CrossDomain\Ai\AiProviderInterface;
use App\Services\CrossDomain\Search\SearchService;
use App\Services\CrossDomain\Search\SearchServiceInterface;
use App\Services\Domain\Media\MediaService;
use App\Services\Domain\Media\MediaServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CalendarRepositoryInterface::class, CalendarRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(LearningMaterialRepositoryInterface::class, LearningMaterialRepository::class);
        $this->app->bind(LessonRepositoryInterface::class, LessonRepository::class);
        $this->app->bind(MediaServiceInterface::class, MediaService::class);
        $this->app->bind(SearchServiceInterface::class, SearchService::class);
        $this->app->bind(PupilRepositoryInterface::class, PupilRepository::class);
        $this->app->bind(TagRepositoryInterface::class, CachedTagRepository::class);
        $this->app->bind(AiProviderInterface::class, function () {
            return (new AiProviderFactory())->make();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
