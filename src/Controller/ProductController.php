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

    public function cart()
    {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {

            $email = $_SESSION["email"];

            $connection = Database::get_connection();
            $sql = "SELECT order_id, status FROM orders WHERE email = '$email' ORDER BY order_id DESC LIMIT 1;";
            $statement = $connection->prepare($sql);
            $statement->execute();

            $result = $statement->fetchAll();

            // CEK APAKAH INVOICE SUDAH DIBUAT DAN STATUS OPEN
            if ((int) isset($result[0]["status"]) == 1 && ($result[0]["status"] == "Open")) {

                // JIKA SUDAH
                $invoice = $result[0]["order_id"];

                $connection = Database::get_connection();

                $sql = "SELECT pd.product_id, pd.name, od.price, od.qty, od.amount, os.status FROM order_detail as od JOIN products as pd ON (pd.product_id = od.product_id) JOIN orders as os ON (os.order_id = od.order_id) WHERE os.status = 'Open' AND os.email = '$email' AND od.order_id = '$invoice' ORDER BY od.id ASC;";

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
            } else if ((int) isset($result[0]["status"]) == 1 && ($result[0]["status"] == "Checkout")) {

                $invoice = $result[0]["order_id"];

                $connection = Database::get_connection();
                $sql = "SELECT total_payment FROM orders WHERE order_id = '$invoice';";
                $statement = $connection->prepare($sql);

                $statement->execute();

                $total_payment = $statement->fetchAll();

                $model = [
                    "title" => "Checkout",
                    "navbar" => Navbar::get_navbar(),
                    "total_payment" => $total_payment,
                    "invoice" => $invoice,
                    "footer" => Footer::get_footer()
                ];

                View::render("ProductController/checkout", $model);
            } else if ((int) isset($result[0]["status"]) == 1 && ($result[0]["status"] == "Verified")) {

                $invoice = $result[0]["order_id"];

                $connection = Database::get_connection();
                $sql = "SELECT total_payment FROM orders WHERE order_id = '$invoice';";
                $statement = $connection->prepare($sql);

                $statement->execute();

                $total_payment = $statement->fetchAll();

                $model = [
                    "title" => "Checkout",
                    "navbar" => Navbar::get_navbar(),
                    "total_payment" => $total_payment,
                    "verified" => true,
                    "disabled_cancel" => true,
                    "invoice" => $invoice,
                    "footer" => Footer::get_footer()
                ];

                View::render("ProductController/checkout", $model);
            } else {

                $model = [
                    "title" => "Empty Cart",
                    "navbar" => Navbar::get_navbar(),
                    "footer" => Footer::get_footer()
                ];

                View::render("ProductController/empty_cart", $model);
            }
        }

        $connection = null;
    }

    public function add_to_cart()
    {
        if ($_SERVER["REQUEST_METHOD"] = "POST") {

            $email = $_SESSION["email"];
            $product_id = $_POST["product_id"];
            $price = $_POST["price"];

            // PENGECEKAN APAKAH SUDAH PERNAH DIBUAT ORDER SEBELUMNYA
            $connection = Database::get_connection();
            $sql = "SELECT order_id, status FROM orders WHERE status = 'Open' and email = '$email';";
            $statement = $connection->prepare($sql);
            $statement->execute();

            $result = $statement->fetchAll();

            // CEK APAKAH INVOICE SUDAH DIBUAT
            if ((int) isset($result[0]["order_id"]) == 1 && $result[0]["status"] == "Open") {

                //  JIKA SUDAH DIBUAT INVOICE
                $invoice = $result[0]["order_id"];

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

                // PENGECEKAN APAKAH SUDAH PERNAH DIBUAT ORDER SEBELUMNYA
                $connection = Database::get_connection();
                $sql = "SELECT status FROM orders WHERE email = '$email';";
                $statement = $connection->prepare($sql);
                $statement->execute();

                $result = $statement->fetchAll();

                if ($result[0]["status"] != "Checkout" && $result[0]["status"] != "Verified") {

                    $invoice = "INV/" . date("Y") . date("m") . date("d") . "/" .  date("H") . date("i") . date("s");

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

    public function total_payment()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $total_payment = (int) $_POST["total_payment"];
            $invoice = $_POST["invoice"];

            // echo $total_payment . "</br>" . $invoice;

            $sql = "UPDATE orders SET total_payment = $total_payment WHERE order_id = '$invoice' AND status = 'Open';";

            $connection = Database::get_connection();
            $connection->query($sql);

            $connection = null;
        }
    }

    public function clean_basket()
    {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {

            $email = $_SESSION["email"];

            $connection = Database::get_connection();
            $sql = "SELECT order_id FROM orders WHERE status = 'Open' AND email = '$email';";
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

                $connection = null;

                self::redirect("/");
            }
        }
    }

    public function update_status()
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

                $invoice = $result[0]["order_id"];

                $sql = "UPDATE orders SET status = 'Checkout' WHERE order_id = '$invoice';";
                $connection->query($sql);

                $connection = null;

                self::redirect("cart");
            }
        }
    }

    public function cancel_order()
    {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {

            $email = $_SESSION["email"];

            $connection = Database::get_connection();
            $sql = "SELECT order_id FROM orders WHERE status = 'Checkout' AND email = '$email';";
            $statement = $connection->prepare($sql);
            $statement->execute();

            $result = $statement->fetchAll();

            // CEK APAKAH INVOICE SUDAH DIBUAT
            if ((int) isset($result[0]["order_id"]) == 1) {

                $invoice = $result[0]["order_id"];

                $sql = "UPDATE orders SET status = 'Open' WHERE order_id = '$invoice';";
                $connection->query($sql);
                $connection = null;

                self::redirect("cart");
            }
        }
    }


    public function upload_resi()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $email = $_SESSION["email"];

            $file_type = $_FILES['payment_receipt']['type'];
            $file_tmp_name = $_FILES['payment_receipt']['tmp_name'];

            $connection = Database::get_connection();
            $sql = "SELECT order_id FROM orders WHERE status = 'Checkout' and email = '$email';";
            $statement = $connection->prepare($sql);
            $statement->execute();

            $result = $statement->fetchAll();

            $invoice = str_replace("/", "", $result[0]["order_id"]);

            $format = str_replace("image/", "", $file_type);

            move_uploaded_file($file_tmp_name, __DIR__ . "/../../public/payment_receipt/" . "$invoice" . "." . "$format");

            // RENDER HALAMAN CHECKOUT DENGAN ALERT SUCCESS UPLOAD
            $invoice = $result[0]["order_id"];

            $connection = Database::get_connection();

            $sql = "SELECT product_id, qty FROM order_detail WHERE order_id = '$invoice';";
            $statement = $connection->prepare($sql);
            $statement->execute();

            foreach ($statement as $result) {
                $product_id = $result['product_id'];
                $product_qty = $result['qty'];

                $sql = "UPDATE products SET qty = qty - '$product_qty' WHERE product_id = '$product_id'";
                $connection->query($sql);
            }

            $sql = "UPDATE orders SET status = 'Verified' WHERE order_id = '$invoice';";
            $connection->query($sql);

            self::redirect("cart");

            $connection = null;
        }
    }
}
