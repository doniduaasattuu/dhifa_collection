<?php

namespace Donid\DhifaCollection\Middleware;

class AuthMiddleware
{
    public function before(): void
    {
        if (!isset($_SESSION['email'])) {
            header("location: /login");
            exit();
        }
    }
}
