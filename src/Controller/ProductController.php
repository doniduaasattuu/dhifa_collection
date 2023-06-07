<?php

namespace Donid\DhifaCollection\Controller;

use Donid\DhifaCollection\Helper\Database;
use Donid\DhifaCollection\App\View;

class ProductController
{
    public function products()
    {
        $connection = Database::get_connection();
        $sql = "SELECT * FROM products ORDER BY id";
        $statement = $connection->prepare($sql);
        $statement->execute();

        $model = [
            "title" => "Products",
            "content" => "Our Products",
            "statement" => $statement
        ];

        View::render("ProductController/show_products", $model);

        $connection = null;
    }
}
