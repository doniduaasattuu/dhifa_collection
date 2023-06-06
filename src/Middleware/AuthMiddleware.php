<?php

namespace Donid\DhifaCollection\Middleware;

class AuthMiddleware
{
    public function before()
    {
        if (!isset($_SESSION["fullname"])) {
            header("location: /login");
        }
    }
}
