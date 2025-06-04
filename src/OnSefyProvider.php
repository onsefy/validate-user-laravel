<?php

namespace OnSefy\Laravel;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use OnSefy\Laravel\Rules\OnSefy;

class OnSefyProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Publish config file
        $this->publishes([
            __DIR__ . '/../config/onsefy.php' => config_path('onsefy.php'),
        ], 'config');

        // Load & publish translations
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'onsefy');
        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/onsefy'),
        ], 'lang');

        // Extend validator with "onsefy" rule
        Validator::extend('onsefy', function ($attribute, $value, $parameters, $validator) {
            $rule = new OnSefy($this->app->make(OnSefyService::class), $parameters);

            // Run validate method directly and pass a fail closure
            $rule->validate($attribute, $value, function ($message) use ($validator, $attribute) {
                $validator->errors()->add($attribute, str_replace(':attribute', $attribute, $message));
            });

            // Return true always since actual failure is handled via $fail closure
            return true;
        });


        // Custom replacer (optional)
        Validator::replacer('onsefy', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute', $attribute, $message);
        });
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/onsefy.php', 'onsefy');

        $this->app->singleton(OnSefyService::class);
        $this->app->alias(OnSefyService::class, 'onsefy');
    }
}
