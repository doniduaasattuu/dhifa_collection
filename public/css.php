<?php

$name = $_GET['name'];
$format = $_GET['format'];

// return __DIR__ . "../dist/style.css";

// $file = '../dist/' . $name . '.' . $format;
$file = '../src/View/HomeController/' . $name . '.' . $format;
// header('Content-type: image/svg+xml');
header('Content-type: text/css');
readfile($file);
return;
