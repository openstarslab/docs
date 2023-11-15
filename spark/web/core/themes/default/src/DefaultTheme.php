<?php

namespace Nulldark\Themes;

use Nulldark\Template\Theme;

class DefaultTheme extends Theme
{
    public function __construct(
        public string $name = "default",
        public string $version = "0.1.0",
        public string $author = ""
    ) {
    }
}
