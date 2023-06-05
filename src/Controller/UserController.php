<?php

namespace Donid\DhifaCollection\Controller;

use Donid\DhifaCollection\App\View;
use Donid\DhifaCollection\Helper\Database;
use Donid\DhifaCollection\Entity\User;

class UserController
{
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

        var_dump($user);
    }

    public function login()
    {

        $model = [
            "title" => "Login",
            "content" => "Halaman Login"
        ];

        View::render("UserController/login", $model);
    }

    public function login_request()
    {

        function redirect($path)
        {
            header("location: $path");
        };

        $username_from_client = trim($_POST["username"], " ");
        $password_from_client = trim($_POST["password"], " ");

        $connection = Database::get_connection();
        $sql = "SELECT * FROM users";
        $statement = $connection->prepare($sql);
        $statement->execute();

        foreach ($statement as $result) {
            if ($username_from_client == $result[0] && $password_from_client == $result[1]) {
                $_SESSION['username'] = $username_from_client;
                redirect("/");
                return;
            }
        }
        redirect("login");
        return;
    }
}
