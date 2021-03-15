<?php

require_once("model/MovieDB.php");
require_once("ViewHelper.php");

class MoviesRESTController {

    public static function getAll() {
        try {
            echo ViewHelper::renderJSON(MovieDB::getAllActivated());
        } catch (InvalidArgumentException $e) {
            echo ViewHelper::renderJSON($e->getMessage(), 404);
        }
    }
    
    public static function get($id) {
        try {
            $movie= MovieDB::get(["id" => $id]);
            $movie["score"] = ScoreDB::getByMovie(["movie_id" => $id]);
            echo ViewHelper::renderJSON($movie);
        } catch (InvalidArgumentException $e) {
            echo ViewHelper::renderJSON($e->getMessage(), 404);
        }
    }
}
