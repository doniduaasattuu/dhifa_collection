<?php

namespace Donid\DhifaCollection\Controller;

use Donid\DhifaCollection\App\View;
use Donid\DhifaCollection\Helper\Database;
use Donid\DhifaCollection\View\Navbar;
use Donid\DhifaCollection\View\Footer;

class HomeController
{

    public function home()
    {
        $email = $_SESSION["email"];

        $connection = Database::get_connection();
        $sql = "SELECT product_id, name, price FROM products";
        $statement = $connection->prepare($sql);
        $statement->execute();

        $products = $statement->fetchAll();

        $model = [
            "title" => "Dhifa Collection",
            "content" => "Dhifa Collection",
            "products" => $products,
            "navbar" => Navbar::get_navbar(),
            "footer" => Footer::get_footer()
        ];

        View::render("HomeController/home", $model);
    }
}
