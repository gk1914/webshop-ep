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
            
            if ($user !== null) {
                if (password_verify($data["pass"], $user["password"])) {
                    echo "jaaaaaaaa";
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
                    //echo ViewHelper::render("view/user-detail.php", [
                    //    "user" => $user
                    //]);
                    $url = BASE_URL;
                    if ($user["type"] == 2) {
                        $url = BASE_URL . "admin";
                    } else if ($user["type"] == 1) {
                        $url = BASE_URL . "salesman";
                    }
                    echo ViewHelper::redirect($url);
                } else {
                    echo "<script>alert('Napačno geslo!');</script>";
                }
            } else {
                echo "napačen email naslov";
                echo "<script>alert('Uporabnik s tem email-om ne obstaja!');</script>";
                //echo ViewHelper::redirect(BASE_URL . "movies");
            }
        } else {
            echo "neustrezen email naslov";
            echo "<script>alert('neustrezen email');</script>";
            //echo ViewHelper::redirect(BASE_URL . "login");
        }
        
        exit();
    }
    
    public static function logout() {
        session_destroy();
        ViewHelper::redirect(BASE_URL . "login");
    }
    
    public static function administrator() {     
        if (isset($_SESSION["id"]) && USER_TYPE[$_SESSION["type"]] === "administrator") {
            echo ViewHelper::render("view/admin.php");
        } else {
            echo "ni prijavljen";
            //ViewHelper::redirect(BASE_URL . "login");
        }
    }
    
    public static function salesman() {
        if (isset($_SESSION["id"]) && USER_TYPE[$_SESSION["type"]] === "prodajalec") {
            echo ViewHelper::render("view/salesman.php");
        } else {
            echo "ni prijavljen";
            //ViewHelper::redirect(BASE_URL . "login");
        }
    }    
    
    public static function employeeList() {
        $type = [
            "type" => 1
        ];
        echo ViewHelper::render("view/user-list.php", [
            "users" => UserDB::getAllByType($type)
        ]);
    }
    
    public static function customerList() {
        $type = [
            "type" => 0
        ];
        echo ViewHelper::render("view/user-list.php", [
            "users" => UserDB::getAllByType($type)
        ]);
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
        }

        echo ViewHelper::render("view/user-edit.php", ["user" => $user]);
    }
    
    public static function edit() {
        $rules = self::getRules();
        
        $data = filter_input_array(INPUT_POST, $rules);
        
        var_dump($_POST);
        var_dump($data);
//exit();
        if (self::checkValues($data)) {
            UserDB::update($data);
            ViewHelper::redirect(BASE_URL . "users?id=" . $data["id"]);
        } else {
            echo "fail";
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
            $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
            UserDB::updatePassword($data);
            ViewHelper::redirect(BASE_URL . "users?id=" . $data["id"]);
        } else {
            echo "napaka";
            self::editForm(UserDB::get($data));
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
            echo "<p>NAPAKAAAAA  - preveri v funkciji checkValues()...</p>";
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
            'activated' => [
                'filter' => FILTER_VALIDATE_BOOLEAN,
                'flags' => FILTER_NULL_ON_FAILURE
            ]/*,
            'type' => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => [
                    'min_range' => 0,
                    'max_range' => 2
                ]
            ],
            'password' => FILTER_SANITIZE_SPECIAL_CHARS*/
        ];
    }
    
    /**
     * Sets value of 'activated' to 1 or 0 accordingly:
     *      1 => true, on, checked
     *      0 => false, null
     */
    public static function set_bool_activated($data) {
        if ($data["activated"]):
            $data["activated"] = 1;
        else:
            $data["activated"] = 0;
        endif;
        
        return $data;
    }
    
}
