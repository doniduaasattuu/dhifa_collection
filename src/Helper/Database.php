<?php

namespace Donid\DhifaCollection\Helper;

class Database
{

    public static function get_connection(): \PDO
    {

        $database = "dhifa_collection";
        $username = "root";
        $password = "";
        $port = "3306";
        $host = "localhost";

        $connection = new \PDO("mysql:host=$host:$port;dbname=$database", $username, $password);
        return $connection;
    }
}
