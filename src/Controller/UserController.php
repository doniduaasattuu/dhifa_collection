<?php

namespace Donid\DhifaCollection\Controller;

use Donid\DhifaCollection\App\View;
use Donid\DhifaCollection\Helper\Database;
use Donid\DhifaCollection\Entity\User;

class UserController
{

    public static function redirect(string $path): void
    {
        header("location: $path");
    }

    public function register()
    {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $model = [
                "title" => "Registration",
                "content" => "Registration"
            ];

            View::render("UserController/register", $model);
        } else if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $email_from_register = trim($_POST["email"], " ");
            $password_from_register = trim($_POST["password"], " ");
            $fullname_from_register = ucwords(trim($_POST["fullname"]));
            $address_from_register = trim($_POST["address"], " ");
            $phone_number_from_register = trim($_POST["phone_number"], " ");

            if (
                $email_from_register == "" || $email_from_register == " " || $email_from_register == null ||
                $password_from_register == "" || $password_from_register == " " || $password_from_register == null ||
                $fullname_from_register == "" || $fullname_from_register == " " || $fullname_from_register == null ||
                $address_from_register == "" || $address_from_register == " " || $address_from_register == null ||
                $phone_number_from_register == "" || $phone_number_from_register == " " || $phone_number_from_register == null
            ) {
                $model = [
                    "title" => "Registration",
                    "content" => "Registration",
                    "field_cannot_empty" => true
                ];

                View::render("UserController/register", $model);
            } else if (
                isset($email_from_register) &&
                isset($password_from_register) &&
                isset($fullname_from_register) &&
                isset($address_from_register) &&
                isset($phone_number_from_register)
            ) {

                $user = new User();

                $user->setUsername($email_from_register);
                $user->setPassword($password_from_register);
                $user->setFullname($fullname_from_register);
                $user->setAddress($address_from_register);
                $user->setPhone_number($phone_number_from_register);

                $connection = Database::get_connection();
                $sql = "SELECT email FROM users WHERE email = '$email_from_register'";
                $statement = $connection->prepare($sql);
                $statement->execute();

                $result = $statement->fetch();

                if ($result == true && $result != null && $result["email"] == $email_from_register) {

                    $model = [
                        "title" => "Registration",
                        "content" => "Registration",
                        "email_duplicate" => true
                    ];

                    $connection = null;

                    View::render("UserController/register", $model);
                } else {
                    $connection = Database::get_connection();
                    $sql = "INSERT INTO users (email, password, fullname, address, phone_number) VALUES ('$email_from_register', '$password_from_register', '$fullname_from_register', '$address_from_register', '$phone_number_from_register');";
                    $connection->query($sql);

                    $connection = null;

                    $model = [
                        "title" => "Login",
                        "content" => "Login",
                        "registration_success" => true
                    ];

                    View::render("UserController/login", $model);
                }
            }
        }
    }

    public function login()
    {
        if (isset($_SESSION["email"])) {

            self::redirect("/");
        } else {

            if ($_SERVER["REQUEST_METHOD"] == "GET") {

                $model = [
                    "title" => "Login",
                    "content" => "Login"
                ];

                View::render("UserController/login", $model);
            } else {
                $email_from_client = trim($_POST["email"], " ");
                $password_from_client = trim($_POST["password"], " ");

                $connection = Database::get_connection();
                $sql = "SELECT * FROM users WHERE email = '$email_from_client'";
                $statement = $connection->prepare($sql);
                $statement->execute();

                $result = $statement->fetch();

                if ($result == true && $result != null && $email_from_client != "" && $password_from_client != "" && $email_from_client != " " && $password_from_client != " " && ($result["email"] == $email_from_client) && ($result["password"] == $password_from_client)) {
                    $_SESSION["email"] = $email_from_client;
                    $_SESSION["fullname"] = $result["fullname"];
                    $_SESSION["address"] = $result["address"];

                    $firstname = explode(" ", $_SESSION["fullname"]);

                    date_default_timezone_set("Asia/Jakarta");

                    $_SESSION["invoice"] = "INV/" . date("Y") . date("m") . date("d") . "/" .  date("H") . date("i");

                    self::redirect("/");
                } else {
                    $model = [
                        "title" => "Login",
                        "content" => "Login",
                        "login" => true
                    ];

                    View::render("UserController/login", $model);
                }
            }
        }
    }

    public function logout()
    {
        session_destroy();
        header("location: /login");
    }
}
