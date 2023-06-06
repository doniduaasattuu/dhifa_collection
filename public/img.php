<?php

$name = $_GET['name'];
$format = $_GET['format'];

// $file = 'img/' . $name . '.png';
$file = '../src/View/HomeController/img/' . $name . '.' . $format;
header('Content-type: image/' . "." . $format);
readfile($file);
return;
