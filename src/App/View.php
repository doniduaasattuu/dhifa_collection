<?php

namespace Donid\DhifaCollection\App;

class View
{
    public static function render(string $path, array $model)
    {
        require_once __DIR__ . "/../View/" . $path . ".php";
    }
}
