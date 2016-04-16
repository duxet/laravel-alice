<?php namespace duxet\Alice;

use duxet\Alice\Console\SeedCommand;
use duxet\Alice\Persisters\Eloquent;
use Illuminate\Support\ServiceProvider;
use Nelmio\Alice\Fixtures\Loader;

class AliceServiceProvider extends ServiceProvider
{
    /**
     * Path to config file.
     *
     * @var string
     */
    protected $configPath = __DIR__ . '/../resources/config/alice.php';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        if (function_exists('config_path')) {
            $publishPath = config_path('alice.php');
        } else {
            $publishPath = base_path('config/alice.php');
        }

        $this->publishes([$this->configPath => $publishPath], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->configPath, 'alice');

        $this->app['alice.loader'] = $this->app->share(
            function ($app) {
                $providers = [];
                $locale = $app['config']->get('alice.locale');
                $seed = $app['config']->get('alice.seed');

                return new Loader($locale, $providers, $seed);
            }
        );

        $this->app['alice.persister'] = $this->app->share(
            function () {
                return new Eloquent();
            }
        );

        $this->registerCommands();
    }

    /**
     * Register console commands.
     *
     * @return void
     */
    public function registerCommands()
    {
        $this->app['command.alice.seed'] = $this->app->share(
            function ($app) {
                return new SeedCommand();
            }
        );

        $this->commands('command.alice.seed');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['alice.loader', 'alice.persister', 'command.alice.seed'];
    }
}
