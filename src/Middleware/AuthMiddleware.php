<?php

namespace Donid\DhifaCollection\Middleware;

class AuthMiddleware
{
    public function before()
    {
        if (!isset($_SESSION["username"])) {
            header("location: /login");
        }
    }
}
