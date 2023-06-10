<?php

namespace Donid\DhifaCollection\Controller;

use Donid\DhifaCollection\App\View;
use Donid\DhifaCollection\Helper\Database;
use Donid\DhifaCollection\View\Navbar;

class HomeController
{

    public function home()
    {
        $connection = Database::get_connection();
        $sql = "SELECT id_product, name, price FROM products";
        $statement = $connection->prepare($sql);
        $statement->execute();

        $products = $statement->fetchAll();

        $model = [
            "title" => "Dhifa Collection",
            "content" => "Dhifa Collection",
            "navbar" => Navbar::get_navbar(),
            "products" => $products
        ];

        View::render("HomeController/home", $model);
    }
}
