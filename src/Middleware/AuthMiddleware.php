<?php

namespace Donid\DhifaCollection\Middleware;

class AuthMiddleware
{
    public function before(): void
    {
        if (!isset($_SESSION['username'])) {
            header("location: /login");
            exit();
        }
    }
}
