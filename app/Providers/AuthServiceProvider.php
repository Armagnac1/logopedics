<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\LearningMaterial;
use App\Models\Lesson;
use App\Models\Pupil;
use App\Models\Tutor;
use App\Policies\LearningMaterialPolicy;
use App\Policies\LessonPolicy;
use App\Policies\MediaPolicy;
use App\Policies\PupilPolicy;
use App\Policies\TutorPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        LearningMaterial::class => LearningMaterialPolicy::class,
        Lesson::class => LessonPolicy::class,
        Pupil::class => PupilPolicy::class,
        Tutor::class => TutorPolicy::class,
        Media::class => MediaPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        Gate::before(function ($user, $ability) {
            return $user->hasRole('superadmin') ? true : null;
        });
    }
}
