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
        $model = [
            "title" => "Buat Akun",
            "content" => "Halaman Pendaftaran"
        ];

        View::render("UserController/register", $model);
    }

    public function create_acount()
    {

        $username_from_register = trim($_POST["username"], " ");
        $password_from_register = $_POST["password"];
        $fullname_from_register = $_POST["fullname"];
        $address_from_register = $_POST["address"];
        $phone_number_from_register = $_POST["phone_number"];

        $user = new User();

        $user->setUsername($username_from_register);
        $user->setPassword($password_from_register);
        $user->setFullname($fullname_from_register);
        $user->setAddress($address_from_register);
        $user->setPhone_number($phone_number_from_register);

        $connection = Database::get_connection();
        $sql = "SELECT username FROM users WHERE username = '$username_from_register'";
        $statement = $connection->prepare($sql);
        $statement->execute();

        $result = $statement->fetchAll();

        // VALIDATE USERNAME IS NOT DUPLICATE
        if (isset($result[0]) && $result[0]["username"] == $username_from_register) {
            // echo "Username " . $result[0]["username"] . " sudah digunakan";
            echo <<<GAGAL
            <script>
                alert("Username sudah digunakan! ⚠️");
                window.location = "/register";
            </script>
            GAGAL;
        } else {
            // CREATE ACOUNT AND INSERT INTO DATABASE USERS
            if ($username_from_register != " " && $username_from_register != "" && $username_from_register != null) {
                $connection = Database::get_connection();
                $connection->query("INSERT INTO users VALUES ('$username_from_register', '$password_from_register', '$fullname_from_register', '$address_from_register', '$phone_number_from_register');");
                $connection = null;

                $model = [
                    "title" => "Registration Success",
                    "Content" => "Registration Success"
                ];

                View::render("UserController/success", $model);
            } else {
                echo <<<GAGAL
                <script>
                    alert("Username tidak boleh kosong! ⚠️");
                    window.location = "/register";
                </script>
                GAGAL;
            }
        }
    }

    public function login()
    {
        if (isset($_SESSION["username"])) {
            self::redirect("/");
        } else {
            $model = [
                "title" => "Login",
                "content" => "Halaman Login"
            ];

            View::render("UserController/login", $model);
        }
    }

    public function logout()
    {
        session_destroy();
        header("location: /login");
    }

    public function login_request()
    {

        $username_from_client = trim($_POST["username"], " ");
        $password_from_client = trim($_POST["password"], " ");

        $connection = Database::get_connection();
        $sql = "SELECT * FROM users";
        $statement = $connection->prepare($sql);
        $statement->execute();

        foreach ($statement as $result) {
            if ($username_from_client == $result[0] && $password_from_client == $result[1]) {

                $_SESSION['username'] = $result[0];
                $_SESSION['fullname'] = $result[2];

                echo <<<HOME
                <script>
                    window.location = "/";
                </script>
                HOME;
                exit();
            }
        }
        self::redirect("/login");
        return;
    }
}
