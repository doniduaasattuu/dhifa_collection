<?php

namespace Donid\DhifaCollection\Controller;

use Donid\DhifaCollection\Helper\Database;
use Donid\DhifaCollection\App\View;
use Donid\DhifaCollection\View\Navbar;

class ProductController
{
    public function products()
    {
        $connection = Database::get_connection();
        $sql = "SELECT * FROM products ORDER BY id_product";
        $statement = $connection->prepare($sql);
        $statement->execute();

        $model = [
            "title" => "Products",
            "content" => "Our Products",
            "navbar" => Navbar::get_navbar(),
            "statement" => $statement
        ];

        View::render("ProductController/show_products", $model);

        $connection = null;
    }

    public function cart()
    {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {


            $connection = Database::get_connection();
            $sql = "SELECT * FROM test";
            $statement = $connection->prepare($sql);
            $statement->execute();

            $result = $statement->fetchAll();

            $model = [
                "title" => "Cart",
                "content" => "Your cart",
                "navbar" => Navbar::get_navbar(),
                "cart" => $result
            ];

            View::render("ProductController/cart", $model);

            $connection = null;
        }
    }

    public function add_to_cart()
    {
        if ($_SERVER["REQUEST_METHOD"] = "POST") {

            $name = $_POST["name"];
            $price = $_POST["price"];
            $qty = $_POST["qty"];
            $amount = $_POST["amount"];

            $connection = Database::get_connection();
            $connection->query("INSERT INTO test (name, price, qty, amount) VALUES ('$name', '$price', '$qty', '$amount')");
            // $connection = null;
        }
    }
}
