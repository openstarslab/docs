<?php

namespace Spark\Core;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

final class TwigLoader implements TwigLoaderInterface
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function load(array $params, string $templatesPath): void
    {
        $templatesPath = __DIR__ . '/templates';
//        $this->spark->path('themes'); <- Zwróci mi patf
//
//        $this->spark->bind(FilesystemLoader::class, $object, true);
//        $this->spark->singleton(FilesystemLoader::class, $object);
        $loader = new FilesystemLoader($templatesPath);
        $twig = new Environment($loader);

        $params = ['title' => 'Welcome to My Site',
            'content' => 'This is the content of the page.',
        ];

        $template = 'example_template.twig';

        $twig->render($template, $params);
    }
}