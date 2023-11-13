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
        //TODO tu bym robił tak że
        // 1 To theme Registry jest początkiem i w nim pobieram path do aktualnego rozszerzenia i resztę danych
        // 2 w Twig loaderze dostaje już te dane i działam z nimi. Funkcja load mi wywoła podstronę odpowiednią
        // 3 Ma otrzymywać nazwę endpointa. Rozkmiń czy tak samo ma się nazywać twig
        // 4 Dodaj też możliwość działania na tym twigu przy pomocy php
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