<?php

// enables sessions for the entire app
session_start();

require_once("controller/MoviesController.php");
require_once("controller/MoviesRESTController.php");
require_once("controller/UsersController.php");
require_once("controller/CartController.php");
require_once("controller/OrdersController.php");

define("BASE_URL", rtrim($_SERVER["SCRIPT_NAME"], "index.php"));
define("IMAGES_URL", rtrim($_SERVER["SCRIPT_NAME"], "index.php") . "static/images/");
define("CSS_URL", rtrim($_SERVER["SCRIPT_NAME"], "index.php") . "static/css/");

define("USER_TYPE", array(
    0 => "stranka",
    1 => "prodajalec",
    2 => "administrator"));

$path = isset($_SERVER["PATH_INFO"]) ? trim($_SERVER["PATH_INFO"], "/") : "";

/* Uncomment to see the contents of variables
  var_dump(BASE_URL);
  var_dump(IMAGES_URL);
  var_dump(CSS_URL);
  var_dump($path);
  exit(); */

// ROUTER: defines mapping between URLS and controllers
$urls = [
    "/^authorized-login$/" => function ($method) {
        if ($method == "POST") {
            UsersController::X509Login();
        }
    },
    "/^login$/" => function ($method) {
        if ($method == "POST") {
            UsersController::login();
        } else {
            UsersController::index();
        }
    },
    "/^logout$/" => function () {
        UsersController::logout();
    },
    "/^register$/" => function ($method) {
        if ($method == "POST") {
            UsersController::register();
        } else {
            UsersController::registerForm();
        }
    },
    "/^add-customer$/" => function ($method) {
        if ($method == "POST") {
            UsersController::register();
        } else {
            UsersController::addCustomerForm();
        }
    },
    "/^add-salesman$/" => function ($method) {
        if ($method == "POST") {
            UsersController::registerSalesman();
        } else {
            UsersController::addSalesmanForm();
        }
    },
    "/^admin$/" => function() {
        UsersController::administrator();
    },
    "/^salesman$/" => function() {
        UsersController::salesman();
    },
    "/^users$/" => function () {
        UsersController::detail();
    },
    "/^users-salesman$/" => function () {
        UsersController::salesmanList();
    },
    "/^users-customer$/" => function () {
        UsersController::customerList();
    },
    "/^users\/edit$/" => function ($method) {
        if ($method == "POST") {
            UsersController::edit();
        } else {
            UsersController::editForm();
        }
    },
    "/^users\/edit-password$/" => function () {
        UsersController::changePassword();
    },
    "/^movies$/" => function () {
        MoviesController::index();
    },
    "/^movies\/add$/" => function ($method) {
        if ($method == "POST") {
            MoviesController::add();
        } else {
            MoviesController::addForm();
        }
    },
    "/^movies\/edit$/" => function ($method) {
        if ($method == "POST") {
            MoviesController::edit();
        } else {
            MoviesController::editForm();
        }
    },
    "/^movies\/delete$/" => function () {
        MoviesController::delete();
    },
    "/^add-score$/" => function () {
        MoviesController::addScore();
    },
    "/^cart$/" => function ($method) {
        if ($method == "POST") {
            CartController::cart();
        } else {
            CartController::index();
        }
    },
    "/^orders$/" => function ($method) {
        /*if ($method == "POST") {
            echo "pooooost";
        } else {*/
            OrdersController::index();
        //}
    },
    "/^orders\/edit$/" => function ($method) {
        if ($method == "POST") {
            OrdersController::edit();
        } else {
            OrdersController::editForm();
        }
    },
    "/^orders\/add$/" => function () {
        OrdersController::add();
    },
    "/^$/" => function () {
        ViewHelper::redirect(BASE_URL . "movies");
    },
        
    "/^api\/movies$/" => function () {
        MoviesRestController::getAll();
    },
    "/^api\/movies\/(\d+)$/" => function ($method, $id) {
        MoviesRestController::get($id);
    }
];

foreach ($urls as $pattern => $controller) {
    if (preg_match($pattern, $path, $params)) {
        try {
            $params[0] = $_SERVER["REQUEST_METHOD"];
            $controller(...$params);
        } catch (InvalidArgumentException $e) {
            ViewHelper::error404();
        } catch (Exception $e) {
            ViewHelper::displayError($e, true);
        }

        exit();
    }
}

ViewHelper::displayError(new InvalidArgumentException("No controller matched."), true);
