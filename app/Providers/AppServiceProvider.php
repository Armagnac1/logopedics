<?php

namespace App\Providers;

use App\Repositories\Abstracts\CalendarRepositoryInterface;
use App\Repositories\Abstracts\LearningMaterialRepositoryInterface;
use App\Repositories\Abstracts\LessonRepositoryInterface;
use App\Repositories\Abstracts\PupilRepositoryInterface;
use App\Repositories\Abstracts\TagRepositoryInterface;
use App\Repositories\Abstracts\UserRepositoryInterface;
use App\Repositories\CalendarRepository;
use App\Repositories\LearningMaterialRepository;
use App\Repositories\LessonRepository;
use App\Repositories\PupilRepository;
use App\Repositories\TagRepository;
use App\Repositories\UserRepository;
use App\Services\Abstracts\MediaServiceInterface;
use App\Services\Abstracts\SearchServiceInterface;
use App\Services\MediaService;
use App\Services\SearchService;
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
        $this->app->bind(TagRepositoryInterface::class, TagRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
    }
}
