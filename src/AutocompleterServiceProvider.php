<?php

namespace Seblhaire\Autocompleter;

use Illuminate\Support\ServiceProvider;

class AutocompleterServiceProvider extends ServiceProvider {

    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'autocompleter');
        $this->publishes([
            __DIR__ . '/../config/autocompleter.php' => config_path('autocompleter.php'),
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/autocompleter'),
            __DIR__ . '/../resources/js/autocompleter.js' => resource_path('js/vendor/seblhaire/autocompleter/autocompleter.js'),
            __DIR__ . '/../resources/css/autocompleter.css' => resource_path('sass/vendor/seblhaire/autocompleter/autocompleter.css')
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        $this->mergeConfigFrom(__DIR__ . '/../config/autocompleter.php', 'autocompleter');
        \App::bind('AutocompleterService', function () {
            return new AutocompleterService;
        });
    }

    public function provides() {
        return [AutocompleterServiceContract::class];
    }
}
