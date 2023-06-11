<?php

namespace Donid\DhifaCollection\Controller;

use Donid\DhifaCollection\Helper\Database;
use Donid\DhifaCollection\App\View;
use Donid\DhifaCollection\View\Navbar;

class ProductController
{
    public static function redirect(string $path): void
    {
        header("location: $path");
    }

    public function products()
    {
        $connection = Database::get_connection();
        $sql = "SELECT * FROM products ORDER BY product_id";
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
            $sql = "SELECT name, price, qty, amount FROM order_detail ORDER BY id ASC";
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

            $email = $_SESSION["email"];

            $connection = Database::get_connection();
            $sql = "SELECT order_id FROM orders WHERE status = 'Open' and email = '$email';";
            $statement = $connection->prepare($sql);
            $statement->execute();

            $result = $statement->fetchAll();

            if ((int) isset($result[0]["order_id"]) == 1) {
                $sql = "INSERT INTO orders (order_id, email, order_date, status) VALUES ('INV/20230611/TEST' , 'doni@gmail.com' , '2023-06-11' , 'Open')";
                $connection->query($sql);
            } else {

                $sql = "DELETE FROM orders WHERE order_id = 'INV/20230611/TEST'";
                $connection->query($sql);
            }

            // =============================
            // USABLE CODE DO NOT DELETE
            // =============================
            // $name = $_POST["name"];
            // $price = $_POST["price"];

            // $connection = Database::get_connection();
            // $statement = $connection->prepare("SELECT name FROM order_detail WHERE name = '$name'");
            // $statement->execute();

            // $result = $statement->fetchAll();

            // if ($result[0]["name"] == $name) {
            //     $connection = Database::get_connection();
            //     $connection->query("UPDATE order_detail SET qty = qty + 1, amount = amount + $price WHERE name = '$name';");
            // } else {
            //     $connection = Database::get_connection();
            //     $connection->query("INSERT INTO order_detail (name, price, amount) VALUES ('$name', '$price', '$price')");
            // }
            // $connection = null;
        }
    }

    public function update_cart_quantity()
    {
        if ($_SERVER["REQUEST_METHOD"] = "POST") {

            $name = $_POST["name"];
            $function = $_POST["function"];
            $amount = $_POST["amount"];

            if ($function == "decrement") {
                $connection = Database::get_connection();

                $sql = "UPDATE order_detail SET qty = qty - 1, amount = '$amount' WHERE name = '$name'";

                $connection->query($sql);
                $connection = null;
            } else if ($function == "increment") {
                $connection = Database::get_connection();

                $sql = "UPDATE order_detail SET qty = qty + 1, amount = '$amount' WHERE name = '$name'";

                $connection->query($sql);
                $connection = null;
            }
        }
    }

    public function clean_basket()
    {
        if ($_SERVER["REQUEST_METHOD"] = "POST") {

            $table = trim($_POST["table"]);

            $connection = Database::get_connection();
            $connection->query("TRUNCATE $table");
            $connection = null;

            // self::redirect("cart");
        }
    }
}
