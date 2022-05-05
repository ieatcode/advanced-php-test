<?php
require 'vendor/autoload.php';
require 'system/Utility.php';
require 'system/Bootstrap.php';

use App\System\Request;
use App\System\Router;

try {
    Router::load('application/routes.php')->direct(Request::uri(), Request::method());
} catch (Exception $e) {
    die($e->getMessage());
}