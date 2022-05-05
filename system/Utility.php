<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;

/**
 * Redirect to new route
 * @param $path
 */
function redirect($path)
{
    header("Location: /{$path}");
}

/**
 * To require a view and extract payload data
 * @param $name
 * @param array $payload
 * @return mixed
 * @throws LoaderError
 * @throws RuntimeError
 * @throws SyntaxError
 */
function view($name, $payload = [])
{
    $loader = new FilesystemLoader("application/views/");
    $twig = new Environment($loader);

    $twig->addFilter(new TwigFilter("row_to_value", function ($stdClassObject) {
        $response = array();
        foreach ($stdClassObject as $key => $value) {
            $response[] = $value;
        }
        return $response;
    }));

    $twig->addFilter(new TwigFilter("field_to_heading", function ($data) {
        $collectData = collect($data);
        $keys = collect($collectData->get(0))->keys();
        return $keys->map(function ($item, $key) {
            return collect(explode('_', $item))
                ->map(function ($item, $key) {
                    return ucfirst($item);
                })
                ->join(' ');
        });
    }));

    extract($payload);

    echo $twig->render("{$name}.html", $payload);
}

