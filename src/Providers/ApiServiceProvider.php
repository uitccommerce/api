<?php

namespace Uitccommerce\Api\Providers;

use Uitccommerce\Api\Facades\ApiHelper;
use Uitccommerce\Api\Http\Middleware\ForceJsonResponseMiddleware;
use Uitccommerce\Base\Facades\DashboardMenu;
use Uitccommerce\Base\Supports\ServiceProvider;
use Uitccommerce\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ReflectionClass;

class ApiServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        if (class_exists('ApiHelper')) {
            AliasLoader::getInstance()->alias('ApiHelper', ApiHelper::class);
        }
    }

    public function boot(): void
    {
        $this
            ->setNamespace('packages/api')
            ->loadRoutes()
            ->loadAndPublishConfigurations(['api', 'permissions'])
            ->loadAndPublishTranslations()
            ->loadMigrations()
            ->loadAndPublishViews();

        if (ApiHelper::enabled()) {
            $this->loadRoutes(['api']);
        }

        $this->app['events']->listen(RouteMatched::class, function () {
            if (ApiHelper::enabled()) {
                $this->app['router']->pushMiddlewareToGroup('api', ForceJsonResponseMiddleware::class);
            }

            DashboardMenu::registerItem([
                'id' => 'cms-packages-api',
                'priority' => 9999,
                'parent_id' => 'cms-core-settings',
                'name' => 'packages/api::api.settings',
                'icon' => null,
                'url' => route('api.settings'),
                'permissions' => ['api.settings'],
            ]);
        });

        $this->app->booted(function () {
            config([
                'scribe.routes.0.match.prefixes' => ['api/*'],
                'scribe.routes.0.apply.headers' => [
                    'Authorization' => 'Bearer {token}',
                    'Api-Version' => 'v1',
                ],
            ]);
        });
    }

    protected function getPath(string $path = null): string
    {
        return __DIR__ . '/../..' . ($path ? '/' . ltrim($path, '/') : '');
    }
}
