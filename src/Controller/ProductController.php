<?php

namespace Donid\DhifaCollection\Controller;

use Donid\DhifaCollection\Helper\Database;
use Donid\DhifaCollection\App\View;
use Donid\DhifaCollection\View\Navbar;
use Donid\DhifaCollection\View\Footer;

class ProductController
{
    public static function redirect(string $path): void
    {
        header("location: $path");
    }

    // public function products()
    // {
    //     $connection = Database::get_connection();
    //     $sql = "SELECT * FROM products ORDER BY product_id";
    //     $statement = $connection->prepare($sql);
    //     $statement->execute();

    //     $model = [
    //         "title" => "Products",
    //         "content" => "Our Products",
    //         "navbar" => Navbar::get_navbar(),
    //         "footer" => Footer::get_footer(),
    //         "statement" => $statement
    //     ];

    //     View::render("ProductController/show_products", $model);

    //     $connection = null;
    // }

    public function cart()
    {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {

            $email = $_SESSION["email"];

            $connection = Database::get_connection();
            $sql = "SELECT order_id FROM orders WHERE status = 'Open' and email = '$email';";
            $statement = $connection->prepare($sql);
            $statement->execute();

            $result = $statement->fetchAll();

            // CEK APAKAH INVOICE SUDAH DIBUAT
            if ((int) isset($result[0]["order_id"]) == 1) {

                // JIKA SUDAH
                $invoice = $result[0]["order_id"];

                $connection = Database::get_connection();

                $sql = "SELECT pd.product_id, pd.name, od.price, od.qty, od.amount FROM order_detail as od JOIN products as pd ON (pd.product_id = od.product_id) JOIN orders as os ON (os.order_id = od.order_id) WHERE os.status = 'Open' AND os.email = '$email' AND od.order_id = '$invoice' ORDER BY od.id ASC;";

                $statement = $connection->prepare($sql);
                $statement->execute();

                $result = $statement->fetchAll();

                $model = [
                    "title" => "Cart",
                    "content" => "Your cart",
                    "navbar" => Navbar::get_navbar(),
                    "footer" => Footer::get_footer(),
                    "cart" => $result,
                    "invoice" => $invoice
                ];

                View::render("ProductController/cart", $model);
            } else {

                $model = [
                    "title" => "Empty Cart",
                    "navbar" => Navbar::get_navbar(),
                    "footer" => Footer::get_footer()
                ];

                View::render("ProductController/empty_cart", $model);
            }

            $connection = null;
        }
    }

    public function add_to_cart()
    {
        if ($_SERVER["REQUEST_METHOD"] = "POST") {

            $email = $_SESSION["email"];
            $product_id = $_POST["product_id"];
            $price = $_POST["price"];


            $connection = Database::get_connection();
            $sql = "SELECT order_id FROM orders WHERE status = 'Open' and email = '$email';";
            $statement = $connection->prepare($sql);
            $statement->execute();

            $result = $statement->fetchAll();

            // CEK APAKAH INVOICE SUDAH DIBUAT
            if ((int) isset($result[0]["order_id"]) == 1) {

                //  JIKA SUDAH DIBUAT INVOICE
                $invoice = $result[0]["order_id"];

                echo $invoice;

                // CEK APAKAH PRODUCT ID SUDAH PERNAH DI INPUT SEBELUMNYA
                $connection = Database::get_connection();

                $sql = "SELECT product_id FROM order_detail WHERE product_id = '$product_id' AND order_id = '$invoice';";
                $statement = $connection->prepare($sql);
                $statement->execute();

                $result = $statement->fetchAll();

                if ((int) isset($result[0]["product_id"]) == 1) {

                    // JIKA SUDAH PERNAH DI INPUT SEBELUMNYA DAN INVOICE SUDAH DIBUAT
                    $sql = "UPDATE order_detail SET qty = qty + 1, amount = amount + $price WHERE product_id = '$product_id' AND order_id = '$invoice';";
                    $connection->query($sql);
                } else {

                    // JIKA BELUM PERNAH DI INPUT SEBELUMNYA DAN INVOICE BELUM DIBUAT
                    $sql = "INSERT INTO order_detail (order_id, product_id, price, amount) VALUES ('$invoice' , '$product_id' , '$price' , '$price')";
                    $connection->query($sql);
                }

                $connection = null;
            } else {

                // JIKA BELUM DIBUAT INVOICE
                // MEMBUAT INVOICE
                $invoice = $_SESSION["invoice"];

                // MEMBUAT INVOICE
                $sql = "INSERT INTO orders (order_id, email) VALUES ('$invoice','$email');";
                $connection->query($sql);

                // MENGINPUT DATA KE DALAM INVOICE
                $sql = "INSERT INTO order_detail (order_id, product_id, price, amount) VALUES ('$invoice' , '$product_id' , '$price' , '$price')";
                $connection->query($sql);

                $connection = null;
            }
        }
    }

    public function update_cart_quantity()
    {
        if ($_SERVER["REQUEST_METHOD"] = "POST") {

            $email = $_SESSION["email"];
            $product_id = $_POST["product_id"];
            $function = $_POST["function"];
            $amount = $_POST["amount"];

            $connection = Database::get_connection();
            $sql = "SELECT order_id FROM orders WHERE status = 'Open' and email = '$email';";
            $statement = $connection->prepare($sql);
            $statement->execute();

            $result = $statement->fetchAll();

            // CEK APAKAH INVOICE SUDAH DIBUAT
            if ((int) isset($result[0]["order_id"]) == 1) {

                $invoice = $result[0]["order_id"];

                if ($function == "decrement") {

                    $connection = Database::get_connection();

                    $sql = "UPDATE order_detail SET qty = qty - 1, amount = '$amount' WHERE product_id = '$product_id' AND order_id = '$invoice'";
                    $connection->query($sql);

                    $connection = null;
                } else if ($function == "increment") {

                    $connection = Database::get_connection();

                    $sql = "UPDATE order_detail SET qty = qty + 1, amount = '$amount' WHERE product_id = '$product_id' AND order_id = '$invoice'";
                    $connection->query($sql);

                    $connection = null;
                }

                $connection = null;
            }
        }
    }

    public function clean_basket()
    {
        if ($_SERVER["REQUEST_METHOD"] = "POST") {

            $email = $_SESSION["email"];

            $connection = Database::get_connection();
            $sql = "SELECT order_id FROM orders WHERE status = 'Open' and email = '$email';";
            $statement = $connection->prepare($sql);
            $statement->execute();

            $result = $statement->fetchAll();

            // CEK APAKAH INVOICE SUDAH DIBUAT
            if ((int) isset($result[0]["order_id"]) == 1) {

                $invoice = $result[0]["order_id"];

                $sql = "DELETE FROM order_detail WHERE order_id ='$invoice';";
                $connection->query($sql);


                $sql = "DELETE FROM orders WHERE order_id = '$invoice';";
                $connection->query($sql);

                self::redirect("cart");
            }
        }
    }
}
