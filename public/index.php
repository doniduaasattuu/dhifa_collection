<?php

require_once __DIR__ . "/../vendor/autoload.php";

session_start();

use Donid\DhifaCollection\Controller\HomeController;
use Donid\DhifaCollection\Controller\UserController;
use Donid\DhifaCollection\Middleware\AuthMiddleware;
use Donid\DhifaCollection\App\Router;
use Donid\DhifaCollection\Controller\ProductController;

// HOME CONTROLLER
Router::add("GET", "/", HomeController::class, "home", [AuthMiddleware::class]);

// USER CONTROLLER
Router::add("GET", "/register", UserController::class, "register");
Router::add("POST", "/create_account", UserController::class, "create_account");
Router::add("GET", "/login", UserController::class, "login");
Router::add("POST", "/login_request", UserController::class, "login_request");
Router::add("GET", "/logout", UserController::class, "logout");

// PRODUCT CONTROLLER
Router::add("GET", "/products", ProductController::class, "products");

Router::run();
