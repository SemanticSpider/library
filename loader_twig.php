<?php
    use Twig\Loader\FilesystemLoader;
    use Twig\Environment;

    $loader = new FilesystemLoader('templates');
    $view = new Environment($loader);