<?php

require_once("model/OrderDB.php");
require_once("model/OrderItemDB.php");
require_once("ViewHelper.php");

class OrdersController {
    
    public static function index() {
        $rules = [
            'id' => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => ['min_range' => 1]
            ],
            'show' => [
                'filter' => FILTER_VALIDATE_REGEXP,
                'options' => [
                    'regexp' => "/^(user|unprocessed|accepted)$/"
                ]
            ]
        ];
        
        $data = filter_input_array(INPUT_GET, $rules);
        
        if (self::checkValues($data)) {
            if ($data["id"]) {
                // prikaži podrobnosti naročila z id == $data["id"]
                self::orderDetail($data);
            } else {
                // prikaži seznam naročil filtriranih na podlagi parametra 'show'
                self::orderListFiltered($data);
            }
        } else {
            if (self::checkType("salesman")) {
                echo ViewHelper::render("view/order-list.php", [
                    "orders" => OrderDB::getAll()
                ]);
            } else {
                ViewHelper::notify("Nepooblaščen dostop - prodajalec ni prijavljen!", BASE_URL . "x509-login");
            }
        }
    }
    
    private static function orderDetail($data) {
        $order = OrderDB::get($data);
        
        // preveri ali je uporabnik prodajalec ali pa stranka, ki je oddala konkretno naročilo
        if (self::checkType("salesman") || (self::checkType("customer") && $_SESSION["id"] == $order["user_id"])) {
            $user = UserDB::get(["id" => $order["user_id"]]);
            $order["user"] = $user["name"] . " " . $user["surname"];

            $orderItems = OrderItemDB::getAllByOrderID(["order_id" => $order["id"]]);
            for ($i = 0; $i < count($orderItems); $i++) {
                $movie = MovieDB::get(["id" => $orderItems[$i]["movie_id"]]);
                $orderItems[$i]["movie_title"] = $movie["title"];
            }

            echo ViewHelper::render("view/order-detail.php", [
                "order" => $order,
                "orderItems" => $orderItems
            ]);
        } else {
            ViewHelper::notify("Nepooblaščen dostop!", BASE_URL);
        }
    }
    
    private static function orderListFiltered($data) {
        switch ($data["show"]) {
            case "user":
                if (self::checkType("customer")) {
                    $user = [
                        "user_id" => $_SESSION["id"]
                    ];
                    echo ViewHelper::render("view/order-list.php", [
                        "orders" => OrderDB::getAllByUser($user)
                    ]);
                } else {
                    ViewHelper::notify("Nepooblaščen dostop!", BASE_URL);
                }
                break;
            case "unprocessed":
                if (self::checkType("salesman")) {
                    $state = [
                        "state" => "neobdelano"
                    ];
                    echo ViewHelper::render("view/order-list.php", [
                        "orders" => OrderDB::getAllByState($state)
                    ]);
                } else {
                    ViewHelper::notify("Nepooblaščen dostop - prodajalec ni prijavljen!", BASE_URL . "x509-login");
                }
                break;
            case "accepted";
                if (self::checkType("salesman")) {
                    $state = [
                        "state" => "potrjeno"
                    ];
                    echo ViewHelper::render("view/order-list.php", [
                        "orders" => OrderDB::getAllByState($state)
                    ]);
                } else {
                    ViewHelper::notify("Nepooblaščen dostop - prodajalec ni prijavljen!", BASE_URL . "x509-login");
                }
                break;
            default:
                ViewHelper::notify("Napaka v parametrih!", BASE_URL);
                break;
        }
    }
    
    public static function editForm() {
        if (!self::checkType("salesman")) {
            ViewHelper::notify("Nepooblaščen dostop - prodajalec ni prijavljen!", BASE_URL . "x509-login");
        }
        
        $rules = [
            'id' => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => ['min_range' => 1]
            ]
        ];
        
        $data = filter_input_array(INPUT_GET, $rules);
        
        if (self::checkValues($data)) {
            $order = OrderDB::get($data);
            if ($order == null) {
                ViewHelper::notify("Naročilo s tem ID-jem ne obstaja!", BASE_URL . "salesman");
            }
                
            $user = UserDB::get(["id" => $order["user_id"]]);
            $order["user"] = $user["name"] . " " . $user["surname"];

            $orderItems = OrderItemDB::getAllByOrderID(["order_id" => $order["id"]]);
            for ($i = 0; $i < count($orderItems); $i++) {
                $movie = MovieDB::get(["id" => $orderItems[$i]["movie_id"]]);
                $orderItems[$i]["movie_title"] = $movie["title"];
            }

            echo ViewHelper::render("view/order-edit.php", [
                "order" => $order,
                "orderItems" => $orderItems
            ]);
        } else {
            ViewHelper::notify("Naročilo s tem ID-jem ne obstaja!", BASE_URL . "salesman");
        }
    }
    
    public static function edit() {
        $rules = [
            'id' => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => ['min_range' => 1]
            ],
            'state' => [
                'filter' => FILTER_VALIDATE_REGEXP,
                'options' => [
                    'regexp' => "/^(potrjeno|preklicano|stornirano)$/"
                ]
            ]
        ];
        
        $data = filter_input_array(INPUT_POST, $rules);
        
        if (self::checkValues($data)) {
            OrderDB::updateState($data);
            echo ViewHelper::render("view/order-list.php", [
                "orders" => OrderDB::getAll()
            ]);
        } else {
            ViewHelper::notify("Napaka pri urejanju!", BASE_URL . "salesman");
        }
        
    }
    
    public static function add() {
        $order = [
            "user_id" => $_SESSION["id"],
            "time_of_order" => date("Y-m-d h:i:s"),
            "price_total" => array_sum($_SESSION["cartPrices"]),
            "state" => "neobdelano"
        ];
        $orderID = OrderDB::insert($order);
        
        foreach ($_SESSION["cart"] as $id => $amount) {
            $orderItem = [
                "order_id" => $orderID,
                "movie_id" => $id,
                "amount" => $amount,
                "price" => $_SESSION["cartPrices"][$id]
            ];
            OrderItemDB::insert($orderItem);
        }
    
        unset($_SESSION["cart"]);
        unset($_SESSION["cartPrices"]);
        ViewHelper::redirect(BASE_URL);
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

