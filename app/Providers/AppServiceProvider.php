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
        $this->app->bind(TagRepositoryInterface::class, TagRepository::class);
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
