<?php

require_once("model/MovieDB.php");
require_once("model/UserDB.php");
require_once("ViewHelper.php");

class UsersController {
    
    public static function index() {
        echo ViewHelper::render("view/user-login.php");
    }
    
    public static function login() {
        var_dump($_POST);
        
        $rules = [
            "email" => [
                'filter' => FILTER_VALIDATE_EMAIL
            ],
            "pass" => [
                'filter' => FILTER_SANITIZE_SPECIAL_CHARS
            ]
        ];
        
        $data = filter_input_array(INPUT_POST, $rules);
        var_dump($data);
        
        if (self::checkValues($data)) {
            $user = UserDB::getByEmail($data);
            var_dump($user);
            
            if ($user !== null && $user["activated"] && $user["type"] == 0) {
                if (password_verify($data["pass"], $user["password"])) {
                    session_regenerate_id();
                    $_SESSION["id"] = $user["id"];
                    $_SESSION["name"] = $user["name"];
                    $_SESSION["surname"] = $user["surname"];
                    $_SESSION["email"] = $user["email"];
                    $_SESSION["surname"] = $user["surname"];
                    $_SESSION["phone"] = $user["phone"];
                    $_SESSION["post_adress_zipcode"] = $user["post_adress_zipcode"];
                    $_SESSION["activated"] = $user["activated"];
                    $_SESSION["type"] = $user["type"];

                    $url = BASE_URL;
                    if ($user["type"] == 2) {
                        $url = BASE_URL . "admin";
                    } else if ($user["type"] == 1) {
                        $url = BASE_URL . "salesman";
                    }
                    ViewHelper::redirect($url);
                } else {
                    ViewHelper::notify("Napačno geslo!", BASE_URL . "login");
                }
            } else {
                ViewHelper::notify("Uporabnik s tem email-om ne obstaja (ali ni aktiviran)!", BASE_URL . "login");
            }
        } else {
            ViewHelper::notify("Neustrezen email naslov!", BASE_URL . "login");
        }
    }
    
    public static function X509Login() {
        var_dump($_POST);
        
        $rules = [
            "email" => [
                'filter' => FILTER_VALIDATE_EMAIL
            ],
            "pass" => [
                'filter' => FILTER_SANITIZE_SPECIAL_CHARS
            ],
            "cert" => ""
        ];
        
        $data = filter_input_array(INPUT_POST, $rules);
        
        $cert_data = openssl_x509_parse($data["cert"]);
        $cert_email = (is_array($cert_data['subject']['emailAddress']) ?
                        $cert_data['subject']['emailAddress'][0] : $cert_data['subject']['emailAddress']);
        
        $authorized_users = UserDB::getAllAuthorized();
        
        if (self::checkValues($data)) {
            $user = UserDB::getByEmail($data);
            
            if ($user !== null && $user["activated"] && $user["type"] > 0) {
                if (in_array($cert_email, $authorized_users) && $cert_email == $user["email"]) {
                    if (password_verify($data["pass"], $user["password"])) {
                        session_regenerate_id();
                        $_SESSION["authorized"] = true;
                        $_SESSION["id"] = $user["id"];
                        $_SESSION["name"] = $user["name"];
                        $_SESSION["surname"] = $user["surname"];
                        $_SESSION["email"] = $user["email"];
                        $_SESSION["surname"] = $user["surname"];
                        $_SESSION["phone"] = $user["phone"];
                        $_SESSION["post_adress_zipcode"] = $user["post_adress_zipcode"];
                        $_SESSION["activated"] = $user["activated"];
                        $_SESSION["type"] = $user["type"];

                        $url = BASE_URL;
                        if ($user["type"] == 2) {
                            $url = BASE_URL . "admin";
                        } else if ($user["type"] == 1) {
                            $url = BASE_URL . "salesman";
                        }
                        ViewHelper::redirect($url);
                    } else {
                        ViewHelper::notify("Napačno geslo!", BASE_URL . "login");
                    }
                } else {
                    ViewHelper::notify("Avtorizacija na podlagi certifikata ni uspela!", BASE_URL . "x509-login");
                }
            } else {
                ViewHelper::notify("Uporabnik s tem email-om ne obstaja (ali ni aktiviran)!", BASE_URL . "x509-login");
            }
        } else {
            ViewHelper::notify("Neustrezen email naslov!", BASE_URL . "x509-login");
        }
    }
    
    public static function logout() {
        session_destroy();
        ViewHelper::redirect(BASE_URL . "login");
    }
    
    public static function register() {
        $rules = self::getRules();
        
        $data = filter_input_array(INPUT_POST, $rules);

        if (self::checkValues($data)) {
            if (!self::checkIfEmailExists($data)) {
                UserDB::insertCustomer($data);
                ViewHelper::redirect(BASE_URL);
            } else {
                ViewHelper::notify("Email naslov je že v uporabi!");
                $data["email"] = "";
                self::registerForm($data);
            }
        } else {
            ViewHelper::notify("Napaka!");
            self::registerForm($data);
        }
    }
    
    public static function registerForm($values = [
        "name" => "",
        "surname" => "",
        "email" => "",
        "phone" => "",
        "post_adress_zipcode" => "",
        "adress" => "",
        "password" => "",
    ]) {
        echo ViewHelper::render("view/register.php", $values);
    }
    
    public static function addCustomerForm($values = [
        "name" => "",
        "surname" => "",
        "email" => "",
        "phone" => "",
        "post_adress_zipcode" => "",
        "adress" => "",
        "password" => "",
    ]) {
        if (self::checkType("salesman")) {
            echo ViewHelper::render("view/add-customer.php", $values);
        } else {
            ViewHelper::notify("Nepooblaščen dostop - prodajalec ni prijavljen!", BASE_URL . "x509-login");
        }
    }
    
    public static function registerSalesman() {
        $rules = self::getRules();
        
        $data = filter_input_array(INPUT_POST, $rules);

        if (self::checkValues($data)) {
            if (!self::checkIfEmailExists($data)) {
                UserDB::insertSalesman($data);
                ViewHelper::redirect(BASE_URL . "users-salesman");
            } else {
                ViewHelper::notify("Email naslov je že v uporabi!");
                $data["email"] = "";
                self::addSalesmanForm($data);
            }
        } else {
            ViewHelper::notify("Napaka!");
            self::addSalesmanForm($data);
        }
    }
    
    public static function addSalesmanForm($values = [
        "name" => "",
        "surname" => "",
        "email" => "",
        "password" => "",
    ]) {
        if (self::checkType("administrator")) {
            echo ViewHelper::render("view/add-salesman.php", $values);
        } else {
            ViewHelper::notify("Nepooblaščen dostop - administrator ni prijavljen!", BASE_URL . "x509-login");
        }
    }
    
    private static function checkIfEmailExists($email) {
        return !empty(UserDB::checkEmail($email));
    }
    
    public static function administrator() {     
        if (self::checkType("administrator")) {
            echo ViewHelper::render("view/admin.php");
        } else {
            ViewHelper::notify("Nepooblaščen dostop - administrator ni prijavljen!", BASE_URL . "x509-login");
        }
    }
    
    public static function salesman() {
        if (self::checkType("salesman")) {
            echo ViewHelper::render("view/salesman.php");
        } else {
            ViewHelper::notify("Nepooblaščen dostop - prodajalec ni prijavljen!", BASE_URL . "x509-login");
        }
    }    
    
    public static function salesmanList() {
        if (self::checkType("administrator")) {
            $type = [
                "type" => 1
            ];
            echo ViewHelper::render("view/user-list.php", [
                "users" => UserDB::getAllByType($type)
            ]);
        } else {
            ViewHelper::notify("Nepooblaščen dostop - administrator ni prijavljen!", BASE_URL . "x509-login");
        }
    }
    
    public static function customerList() {
        if (self::checkType("salesman")) {
            $type = [
                "type" => 0
            ];
            echo ViewHelper::render("view/user-list.php", [
                "users" => UserDB::getAllByType($type)
            ]);
        } else {
            ViewHelper::notify("Nepooblaščen dostop - prodajalec ni prijavljen!", BASE_URL . "x509-login");
        }
    }
    
    public static function detail() {
        $rules = [
            "id" => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => ['min_range' => 1]
            ]
        ];

        $data = filter_input_array(INPUT_GET, $rules);
        
        if (self::checkValues($data)) {
            echo ViewHelper::render("view/user-detail.php", [
                "user" => UserDB::get($data)
            ]);
        } else {
            echo ViewHelper::render("view/user-list.php", [
                "users" => UserDB::getAll()
            ]);
        }
    }
    
    public static function editForm($user = []) {      
        if (empty($user)) {
            $rules = [
                "id" => [
                    'filter' => FILTER_VALIDATE_INT,
                    'options' => ['min_range' => 1]
                ]
            ];

            $data = filter_input_array(INPUT_GET, $rules);

            if (!self::checkValues($data)) {
                throw new InvalidArgumentException();
            }

            $user = UserDB::get($data);
            
            if ($user == null) {
                ViewHelper::notify("Uporabnik ne obstaja!", BASE_URL . "users");
            }
        }
        
        // preveri ali ima trenutni uporabnik dovoljenje za urejanje profila
        if (isset($_SESSION["id"]) && ($_SESSION["id"] == $user["id"] || $_SESSION["type"]-1 == $user["type"])) {
            echo ViewHelper::render("view/user-edit.php", ["user" => $user]);
        } else {
            ViewHelper::notify("Nimate dovoljenja za urejanje!!", BASE_URL);
        }
    }
    
    public static function edit() {
        $rules = self::getRules();
        
        $data = filter_input_array(INPUT_POST, $rules);

        if (self::checkValues($data)) {
            if ($data["type"] == 0) { 
                UserDB::updateCustomer($data);
            } else {
                UserDB::updateEmployee($data);
            }
            ViewHelper::redirect(BASE_URL . "users?id=" . $data["id"]);
        } else {
            ViewHelper::notify("Napaka!");
            self::editForm($data);
        }
    }
    
    public static function changePassword() {
        $rules = [
            "id" => [
                    'filter' => FILTER_VALIDATE_INT,
                    'options' => ['min_range' => 1]
            ],
            "password" => FILTER_SANITIZE_SPECIAL_CHARS
        ];
        
        $data = filter_input_array(INPUT_POST, $rules);
        
        var_dump(INPUT_POST);
        var_dump($data);
        
        if (self::checkValues($data)) {
            UserDB::updatePassword($data);
            ViewHelper::redirect(BASE_URL . "users?id=" . $data["id"]);
        } else {
            ViewHelper::notify("Napaka!");
            self::editForm(UserDB::get($data));
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
            if ($key === "activated") {
                $result = $result && $value !== null;
            } else {
                $result = $result && $value !== false;
            }
        }
        
        if (!$result):
            echo "<p>napaka</p>";
        endif;
        
        return $result;
    }
    
    private static function getRules() {
        return [
            'id' => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => ['min_range' => 1]
            ],
            'name' => FILTER_SANITIZE_SPECIAL_CHARS,
            'surname' => FILTER_SANITIZE_SPECIAL_CHARS,
            'email' => FILTER_VALIDATE_EMAIL,
            'phone' => FILTER_SANITIZE_SPECIAL_CHARS,
            'post_adress_zipcode' => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => [
                    'min_range' => 1000,
                    'max_range' => 9999
                ]
            ],
            'adress' => FILTER_SANITIZE_SPECIAL_CHARS,
            'activated' => [
                'filter' => FILTER_VALIDATE_BOOLEAN,
                'flags' => FILTER_NULL_ON_FAILURE
            ],
            'type' => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => [
                    'min_range' => 0,
                    'max_range' => 2
                ]
            ],
            'password' => FILTER_SANITIZE_SPECIAL_CHARS
        ];
    }
    
}
