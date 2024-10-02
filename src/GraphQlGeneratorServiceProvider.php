<?php

namespace Emmanuelsaleem\Graphqlgenerator;

use Illuminate\Support\ServiceProvider;

class GraphQlGeneratorServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            \Emmanuelsaleem\Graphqlgenerator\Console\Commands\GenerateClasses::class,
        ]);
    }

    
    public function boot()
    {
        // Bootstrapping logic if needed.
    }
}
