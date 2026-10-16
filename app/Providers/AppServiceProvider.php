<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\News;
use App\Models\Page;
use App\Observers\MenuItemObserver;
use App\Observers\PageObserver;
use App\Policies\ContentPolicy;
use App\Support\MenuResolver;
use App\Support\SitePresentation;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Inertia\ExceptionResponse;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->registerObservers();
        $this->registerPolicies();
        $this->shareInertiaProps();
        $this->configureErrorPages();
    }

    /**
     * Render halaman error Inertia untuk request web (PRD 6.6 & 15).
     */
    protected function configureErrorPages(): void
    {
        Inertia::handleExceptionsUsing(function (ExceptionResponse $response): ?ExceptionResponse {
            $request = $response->request;

            // Biarkan request API/JSON memakai respons error bawaan Laravel.
            if ($request->expectsJson() || $request->is('api/*')) {
                return null;
            }

            if (in_array($response->statusCode(), [403, 404, 500, 503], true)) {
                return $response->render('Error', [
                    'status' => $response->statusCode(),
                ])->withSharedData();
            }

            return null;
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }

    protected function registerObservers(): void
    {
        Page::observe(PageObserver::class);
        MenuItem::observe(MenuItemObserver::class);
    }

    protected function registerPolicies(): void
    {
        Gate::policy(Page::class, ContentPolicy::class);
        Gate::policy(News::class, ContentPolicy::class);
        Gate::policy(Menu::class, ContentPolicy::class);
        Gate::policy(MenuItem::class, ContentPolicy::class);
    }

    protected function shareInertiaProps(): void
    {
        Inertia::share([
            'navigation' => function () {
                try {
                    $build = function (string $location): array {
                        return Cache::remember("menu-{$location}", 3600, function () use ($location) {
                            $menu = Menu::query()->where('location', $location)->first();
                            if (! $menu) {
                                return [];
                            }

                            return MenuResolver::tree($menu->items()->with(['page', 'department'])->get());
                        });
                    };

                    return [
                        'header' => $build('header'),
                        'footer' => $build('footer'),
                    ];
                } catch (\Throwable) {
                    return ['header' => [], 'footer' => []];
                }
            },
            'siteSettings' => function () {
                try {
                    return SitePresentation::general();
                } catch (\Throwable) {
                    return null;
                }
            },
            'siteTheme' => function () {
                try {
                    return SitePresentation::theme();
                } catch (\Throwable) {
                    return null;
                }
            },
        ]);
    }
}
