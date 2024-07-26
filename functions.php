<?php 

function dd($value) {
    echo "<pre>";
    var_dump($value);
    echo "</pre>";

    die();
}

function abort($code = 404) {
    require "views/error/{$code}.php";
}