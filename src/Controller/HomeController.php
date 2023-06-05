<?php

namespace Donid\DhifaCollection\Controller;

use Donid\DhifaCollection\App\View;

class HomeController
{

    public function home()
    {

        $model = [
            "title" => "Dhifa Collection",
            "content" => "Dhifa Collection"
        ];

        View::render("HomeController/home", $model);
    }
}
