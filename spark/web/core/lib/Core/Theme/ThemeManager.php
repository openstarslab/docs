<?php

namespace Spark\Core\Theme;

use Spark\Core\Theme\Engine\TwigEngine;

class ThemeManager implements ThemeManagerInterface
{
    private ?EngineINterface $engine;

    public function __construct(
        protected ContainerInterface $spark
    ) {

    }

    public function getEngine(): EngineInterface
    {
        if ($this->engine === null) {
            $engineClass = $this->spark->getConfig('render.engine');

            $this->engine = new $engineClass();
        }

        return $this->engine;
    }

    public function render(string $template): array
    {
        return $this->getEngine()->loadTemplate($template);
    }

}