<?php

require_once("model/MovieDB.php");
require_once("model/UserDB.php");
require_once("ViewHelper.php");

class CartController {
    
    public static function index() {
        if (self::checkType("customer")) {
            echo ViewHelper::render("view/cart.php");
        } else {
            ViewHelper::notify("Dostop do košarice mogoč samo za prijavljene stranke!", BASE_URL);
        }
    }
    
    public static function cart() {
        if (!self::checkType("customer")) {
            ViewHelper::notify("Dostop do košarice mogoč samo za prijavljene stranke!", BASE_URL);
        }
        
        $rules = [
            'do' => [
                'filter' => FILTER_VALIDATE_REGEXP,
                'options' => [
                    'regexp' => "/^(add_to_cart|update_cart|empty_cart|checkout)$/"
                ]
            ],
            'id' => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => ['min_range' => 1]
            ],
            'amount' => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => ['min_range' => 0]
            ]
        ];
        
        $data = filter_input_array(INPUT_POST, $rules);
        
        if (self::checkValues($data)) {
            switch ($data["do"]) {
                case "add_to_cart":
                    $movie = MovieDB::get($data);
                    if (isset($_SESSION["cart"][$movie["id"]])) {
                        $_SESSION["cart"][$movie["id"]] ++;
                        $_SESSION["cartPrices"][$movie["id"]] += $movie["price"];
                    } else {
                        $_SESSION["cart"][$movie["id"]] = 1;
                        $_SESSION["cartPrices"][$movie["id"]] = $movie["price"];
                    }
                    ViewHelper::redirect(BASE_URL . "cart");
                    break;
                case "update_cart":
                    $movie = MovieDB::get($data);
                    if (isset($_SESSION["cart"][$movie["id"]])) {
                        if ($data["amount"] > 0) {
                            $_SESSION["cart"][$movie["id"]] = $data["amount"];
                            $_SESSION["cartPrices"][$movie["id"]] = $data["amount"] * $movie["price"];
                        } else {
                            unset($_SESSION["cart"][$movie["id"]]);
                            unset($_SESSION["cartPrices"][$movie["id"]]);
                        }
                    }
                    ViewHelper::redirect(BASE_URL . "cart");
                    break;
                case "empty_cart":
                    unset($_SESSION["cart"]);
                    unset($_SESSION["cartPrices"]);
                    ViewHelper::redirect(BASE_URL . "cart");
                    break;
                case "checkout":
                    echo ViewHelper::render("view/cart-checkout.php");
                default:
                    break;
            }
        } else {
            ViewHelper::notify("Napaka pri upravljanju košarice!", BASE_URL);
        }
    }
    
    private static function checkType($type) {
        switch ($type) {
            case "customer":
                return isset($_SESSION["id"]) && $_SESSION["type"] == 0;
            case "salesman":
                return isset($_SESSION["id"]) && $_SESSION["type"] == 1 && isset($_SESSION["authorized"]) && $_SESSION["authorized"];
            case "administrator":
                return isset($_SESSION["id"]) && $_SESSION["type"] == 2 && isset($_SESSION["authorized"]) && $_SESSION["authorized"];
            default:
                return false;
        }
    }
    
    /**
     * Returns TRUE if given $input array contains no FALSE values
     * @param type $input
     * @return type
     */
    private static function checkValues($input) {
        if (empty($input)) {
            return FALSE;
        }
        
        $result = TRUE;
        foreach ($input as $key => $value) {
            $result = $result && $value !== false;
        }
        
        if (!$result):
            echo "<p>napaka</p>";
        endif;
        
        return $result;
    }
    
}
