<?php

namespace Spark\Core\Theme;

use Spark\Core\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->spark->singleton(ThemeManagerInterface::class, ThemeManager::class);
    }

    public function boot(): void
    {
        $this->spark->make(ThemeManagerInterface::class)->doLoadThemes();
    }

}