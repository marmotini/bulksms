<?php namespace bulksms;

// Autooad the vendor dependency first.
include_once "vendor/autoload.php";

define('BASE_PATH', realpath(dirname(__FILE__)));

// Add autoload callback to enable easy include of classes and files. Call back function includes file
// in the bulksms repository. No complex parsing of filename and classname is performed inorder to keep the
// function simple and clear.
spl_autoload_register(function ($class) {
    $filename = BASE_PATH . '/../' . str_replace('\\', '/', strtolower($class)) . '.php';
    include_once $filename;
});
