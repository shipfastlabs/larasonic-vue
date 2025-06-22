<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use Prism\Prism\Prism;
use Carbon\CarbonImmutable;
use Knuckles\Scribe\Scribe;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;
use Prism\Prism\Enums\Provider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Vite;
use Prism\Prism\Facades\PrismServer;
use Prism\Prism\Text\PendingRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Knuckles\Camel\Extraction\ExtractedEndpointData;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        /**
         * This is optional, but it's recommended to register Telescope in local environment.
         * You are free to remove this if you don't want to use Telescope.
         * Remove the migration files if you don't want to use Telescope.
         *
         * @see https://laravel.com/docs/telescope
         * @see migrations/0001_01_01_000009_create_telescope_entries_table.php
         */
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureCommands();
        $this->configureDates();
        $this->configureModels();
        $this->configureUrl();
        $this->configureVite();
        $this->configurePrisms();
        $this->configureScribeDocumentation();
        $this->configureRateLimiting();
    }

    /**
     * It's recommended to use CarbonImmutable as it's immutable and thread-safe to avoid issues with mutability.
     *
     * @see https://dyrynda.com.au/blog/laravel-immutable-dates
     */
    private function configureDates(): void
    {
        Date::use(CarbonImmutable::class);
    }

    /**
     * Configure the application's Scribe documentation.
     *
     * @see https://scribe.knuckles.wtf/laravel/
     */
    private function configureScribeDocumentation(): void
    {
        if (class_exists(Scribe::class)) {
            Scribe::beforeResponseCall(function (HttpFoundationRequest $request, ExtractedEndpointData $endpointData): void {
                $user = User::query()->first();
                if ($user) {
                    Sanctum::actingAs($user, ['*']);
                }
            });
        }
    }

    /**
     * Configure the application's commands.
     */
    private function configureCommands(): void
    {
        DB::prohibitDestructiveCommands(App::isProduction());
    }

    /**
     * Configure the application's models.
     * This is optional, but it's recommended to enable strict mode and disable mass assignment.
     *
     * @see https://laravel.com/docs/eloquent#configuring-eloquent-strictness
     */
    private function configureModels(): void
    {
        Model::shouldBeStrict();

        Model::unguard();
    }

    /**
     * Configure the application's URL.
     * This is optional, but it's recommended to force HTTPS in production.
     *
     * @see https://laravel.com/docs/octane#serving-your-application-via-https
     */
    private function configureUrl(): void
    {
        URL::forceHttps(App::isProduction());
    }

    /**
     * Configure the application's Vite loading strategy.
     * This is optional, but it's recommended to use aggressive prefetching so the UI feels snappy.
     */
    private function configureVite(): void
    {
        Vite::useAggressivePrefetching();
    }

    /**
     * Configure the application's Prisms.
     * This is optional to demonstrate how to register a Prism.
     * If you don't use AI, you can remove this method.
     *
     * @see https://prism.echolabs.dev/getting-started/introduction.html
     */
    private function configurePrisms(): void
    {
        PrismServer::register(
            'Larasonic Small',
            fn (): PendingRequest => Prism::text()->using(Provider::Gemini, 'gemini-2.0-flash-lite')
                ->withSystemPrompt(view('prompts.system')->render())
                ->withMaxTokens(100)
        );

        PrismServer::register(
            'Larasonic Medium',
            fn (): PendingRequest => Prism::text()->using(Provider::Gemini, 'gemini-2.0-flash-lite')
                ->withSystemPrompt(view('prompts.system')->render())
                ->withMaxTokens(150)
        );

        PrismServer::register(
            'Larasonic Large',
            fn (): PendingRequest => Prism::text()->using(Provider::Gemini, 'gemini-2.0-flash-lite')
                ->withSystemPrompt(view('prompts.system')->render())
                ->withMaxTokens(250)
        );
    }

    /**
     * Configure the application's rate limiting.
     *
     * @see https://laravel.com/docs/11.x/routing#rate-limiting
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('login-link', fn (Request $request) => $request->email ? Limit::perHour(5)->by($request->email) : Limit::perHour(5)->by($request->ip()));
    }
}
