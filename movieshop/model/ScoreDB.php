<?php

require_once 'model/AbstractDB.php';

class ScoreDB extends AbstractDB {

    public static function insert(array $params) {
        return parent::modify("INSERT INTO scores (movie_id, user_id, score) "
                        . " VALUES (:movie_id, :user_id, :score)", $params);
    }

    public static function update(array $params) {
        return parent::modify("UPDATE scores SET score = :score"
                        . " WHERE movie_id = :movie_id AND user_id = :user_id", $params);
    }

    public static function delete(array $params) {
        return parent::modify("DELETE FROM scores WHERE movie_id = :movie_id AND user_id = :user_id", $params);
    }

    public static function get(array $params) {
        $scores = parent::query("SELECT score"
                        . " FROM scores"
                        . " WHERE movie_id = :movie_id AND user_id = :user_id", $params);
        
        if (count($scores) == 1) {
            return $scores[0]["score"];
        } else {
            return null;
        }
    }

    public static function getByMovie($movie_id) {
        $scores = parent::query("SELECT score"
                            . " FROM scores"
                            . " WHERE movie_id = :movie_id", $movie_id);
        if (count($scores) == 0) {
            return 0;
        } else {
            $sum = 0;
            foreach ($scores as $s) {
                $sum += $s["score"];
            }
            return $sum/ count($scores);
        }
    }

    public static function getAll() {}

}
