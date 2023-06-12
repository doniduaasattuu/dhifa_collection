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
        // JIKA USER MENGAKSES HALAMAN REGISTRASI
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $model = [
                "title" => "Registration",
                "content" => "Registration"
            ];

            // RENDER HALAMAN REGISTRASI
            View::render("UserController/register", $model);
        } else if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // MENERIMA SEMUA DATA YANG DI INPUT USER
            $email_from_register = trim($_POST["email"], " ");
            $password_from_register = trim($_POST["password"], " ");
            $fullname_from_register = ucwords(trim($_POST["fullname"]));
            $address_from_register = trim($_POST["address"], " ");
            $phone_number_from_register = trim($_POST["phone_number"], " ");

            if (
                // MELAKUKAN PENGECEKAN APAKAH DATA YANG DI INPUT USER ADALAH TIDAK VALID
                $email_from_register == "" || $email_from_register == " " || $email_from_register == null ||
                $password_from_register == "" || $password_from_register == " " || $password_from_register == null ||
                $fullname_from_register == "" || $fullname_from_register == " " || $fullname_from_register == null ||
                $address_from_register == "" || $address_from_register == " " || $address_from_register == null ||
                $phone_number_from_register == "" || $phone_number_from_register == " " || $phone_number_from_register == null
            ) {

                // JIKA TIDAK VALID MAKA
                $model = [
                    "title" => "Registration",
                    "content" => "Registration",
                    "field_cannot_empty" => true
                ];

                // RENDER HALAMAN REGISTRASI DENGAN ALERT "Field cannot empty! ⚠️"
                View::render("UserController/register", $model);
            } else if (

                // MELAKUKAN PENGECEKAN APAKAH DATA YANG DI INPUT USER LENGKAP
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

                // MELAKUKAN PENGECEKAN KEMBALI APAKAH EMAIL YANG DI INPUT USER ADALAH DUPLICATE
                $connection = Database::get_connection();
                $sql = "SELECT email FROM users WHERE email = '$email_from_register'";
                $statement = $connection->prepare($sql);
                $statement->execute();

                $result = $statement->fetch();

                if ($result == true && $result != null && $result["email"] == $email_from_register) {

                    // JIKA DUPLICATE MAKA
                    $model = [
                        "title" => "Registration",
                        "content" => "Registration",
                        "email_duplicate" => true
                    ];

                    $connection = null;

                    // RENDER HALAMAN REGISTRASI DENGAN ALERT "Email is used! ⚠️"
                    View::render("UserController/register", $model);
                } else {

                    // JIKA SEMUA DATA VALID DAN EMAIL TIDAK DUPLICATE MAKA MENGINPUT SEMUA DATA KE DALAM DATABASE USERS
                    $connection = Database::get_connection();
                    $sql = "INSERT INTO users (email, password, fullname, address, phone_number) VALUES ('$email_from_register', '$password_from_register', '$fullname_from_register', '$address_from_register', '$phone_number_from_register');";
                    $connection->query($sql);

                    $connection = null;

                    $model = [
                        "title" => "Login",
                        "content" => "Login",
                        "registration_success" => true
                    ];

                    // RENDER HALAMAN LOGIN DENGAN ALERT REGISTRATION SUCCESS
                    View::render("UserController/login", $model);
                }
            }
        }
    }

    public function login()
    {
        // JIKA SUDAH PERNAH LOGIN
        if (isset($_SESSION["email"])) {

            // RENDER HALAMAN UTAMA
            self::redirect("/");
        } else {

            // JIKA BELUM LOGIN => RENDER HALAMAN LOGIN
            if ($_SERVER["REQUEST_METHOD"] == "GET") {

                $model = [
                    "title" => "Login",
                    "content" => "Login"
                ];

                // RENDER HALAMAN LOGIN
                View::render("UserController/login", $model);
            } else if ($_SERVER["REQUEST_METHOD"] == "POST") {

                // USER MEMASUKKAN EMAIL DAN PASSWORD DARI HALAMAN LOGIN
                $email_from_client = trim($_POST["email"], " ");
                $password_from_client = trim($_POST["password"], " ");

                // MELAKUKAN PENGECEKAN APAKAH EMAIL ADA DI DALAM DATABASE
                $connection = Database::get_connection();
                $sql = "SELECT * FROM users WHERE email = '$email_from_client'";
                $statement = $connection->prepare($sql);
                $statement->execute();

                $result = $statement->fetch();

                // JIKA EMAIL ADA DALAM DATABASE DAN EMAIL YANG DI INPUT USER ADALAH VALID
                if ($result == true && $result != null && $email_from_client != "" && $password_from_client != "" && $email_from_client != " " && $password_from_client != " " && ($result["email"] == $email_from_client) && ($result["password"] == $password_from_client)) {
                    $_SESSION["email"] = $email_from_client;
                    $_SESSION["fullname"] = $result["fullname"];
                    $_SESSION["address"] = $result["address"];

                    date_default_timezone_set("Asia/Jakarta");

                    // MEMBUAT INVOICE
                    $_SESSION["invoice"] = "INV/" . date("Y") . date("m") . date("d") . "/" .  date("H") . date("i");

                    // RENDER HALAMAN UTAMA
                    self::redirect("/");
                } else {

                    // JIKA EMAIL TIDAK VALID ATAU PASSWORD YANG DIMASUKKAN USER SALAH
                    $model = [
                        "title" => "Login",
                        "content" => "Login",
                        "login" => true
                    ];

                    // RENDER HALAMAN LOGIN DENGAN ALERT "Email or password is wrong! ⚠️"
                    View::render("UserController/login", $model);
                }
            }
        }
    }

    public function logout()
    {
        // MENGAPUS SEMUA SESI DAN REDIRECT KE HALAMAN LOGIN
        session_destroy();
        header("location: /login");
    }
}
