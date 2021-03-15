<?php

require_once("model/MovieDB.php");
require_once("model/ScoreDB.php");
require_once("ViewHelper.php");

class MoviesController {

    public static function index() {
        $rules = [
            "id" => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => ['min_range' => 1]
            ]
        ];

        $data = filter_input_array(INPUT_GET, $rules);
        
        if (isset($_SESSION["id"])) {
            if (!isset($_SERVER["HTTPS"])) {
                $url = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
                header("Location: " . $url);
            }
        }
        
        if (self::checkValues($data)) {
            $score = ScoreDB::getByMovie(["movie_id" => $data["id"]]);
            echo ViewHelper::render("view/movie-detail.php", [
                "movie" => MovieDB::get($data),
                "score" => $score
            ]);
        } else {
            // stranka ali anonimni uporabnik
            if (self::checkType("customer") || !isset($_SESSION["id"])) {
                echo ViewHelper::render("view/movie-list.php", [
                    "movies" => MovieDB::getAllActivated()
                ]);
            // zaposleni               
            } else {
                echo ViewHelper::render("view/movie-list.php", [
                    "movies" => MovieDB::getAll()
                ]);
            }
        }
    }

    public static function addForm($values = [
        "title" => "",
        "director" => "",
        "year" => "",
        "runlength" => "",
        "description" => "",
        "price" => ""
    ]) {
        if (self::checkType("salesman")) {
            echo ViewHelper::render("view/movie-add.php", $values);
        } else {
            ViewHelper::notify("Nepooblaščen dostop - prodajalec ni prijavljen!", BASE_URL . "x509-login");
        }
    }

    public static function add() {
        $data = filter_input_array(INPUT_POST, self::getRules());

        if (self::checkValues($data)) {
            $id = MovieDB::insert($data);
            echo ViewHelper::redirect(BASE_URL . "movies?id=" . $id);
        } else {
            self::addForm($data);
        }
    }

    public static function editForm($movie = []) {
        if (!self::checkType("salesman")) {
            ViewHelper::notify("Nepooblaščen dostop - prodajalec ni prijavljen!", BASE_URL . "x509-login");
        }
        
        if (empty($movie)) {
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

            $movie = MovieDB::get($data);
        }

        echo ViewHelper::render("view/movie-edit.php", ["movie" => $movie]);
    }

    public static function edit() {
        $rules = self::getRules();
        $rules["id"] = [
            'filter' => FILTER_VALIDATE_INT,
            'options' => ['min_range' => 1]
        ];
        $data = filter_input_array(INPUT_POST, $rules);

        if (self::checkValues($data)) {
            MovieDB::update($data);
            ViewHelper::redirect(BASE_URL . "movies?id=" . $data["id"]);
        } else {
            self::editForm($data);
        }
    }

    public static function delete() {
        $rules = [
            'delete_confirmation' => FILTER_REQUIRE_SCALAR,
            'id' => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => ['min_range' => 1]
            ]
        ];
        $data = filter_input_array(INPUT_POST, $rules);

        if (self::checkValues($data)) {
            MovieDB::delete($data);
            $url = BASE_URL . "movies";
        } else {
            if (isset($data["id"])) {
                $url = BASE_URL . "movies/edit?id=" . $data["id"];
            } else {
                $url = BASE_URL . "movies";
            }
        }

        ViewHelper::redirect($url);
    }
    
    public static function addScore() {
        $rules = [
            'score' => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => [
                    'min_range' => 1,
                    'max_range' => 5
                ]
            ],
            'movie_id' => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => ['min_range' => 1]
            ], 
            'user_id' => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => ['min_range' => 1]
            ]
        ];
        
        $data = filter_input_array(INPUT_POST, $rules);
        
        if (self::checkValues($data)) {
            if (ScoreDB::get($data) == null) {
                ScoreDB::insert($data);
            } else {
                ScoreDB::update($data);
            }
            echo ViewHelper::render("view/movie-detail.php", [
                "movie" => MovieDB::get(["id" => $data["movie_id"]]),
                "score" => ScoreDB::getByMovie(["movie_id" => $data["movie_id"]])
            ]);
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
            echo "<p>napaka pri vnosu podatkov</p>";
        endif;
        
        return $result;
    }

    /**
     * Returns an array of filtering rules for manipulation of movies
     * @return type
     */
    private static function getRules() {
        return [
            'title' => FILTER_SANITIZE_SPECIAL_CHARS,
            'director' => FILTER_SANITIZE_SPECIAL_CHARS,
            'year' => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => [
                    'min_range' => 1800,
                    'max_range' => date("Y")
                ]
            ],
            'runlength' => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => [
                    'min_range' => 30,
                    'max_range' => 300
                ]
            ],
            'description' => FILTER_SANITIZE_SPECIAL_CHARS,
            'activated' => [
                'filter' => FILTER_VALIDATE_BOOLEAN,
                'flags' => FILTER_NULL_ON_FAILURE
            ],
            'price' => FILTER_VALIDATE_FLOAT
        ];
    }

}
