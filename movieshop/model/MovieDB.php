<?php

require_once 'model/AbstractDB.php';

class MovieDB extends AbstractDB {

    public static function insert(array $params) {
        return parent::modify("INSERT INTO movies (title, director, year, runlength, description, activated, price) "
                        . " VALUES (:title, :director, :year, :runlength, :description, :activated, :price)", $params);
    }

    public static function update(array $params) {
        return parent::modify("UPDATE movies SET title = :title, director = :director, year = :year, runlength = :runlength,"
                        . " description = :description, activated = :activated, price = :price"
                        . " WHERE id = :id", $params);
    }

    public static function delete(array $id) {
        return parent::modify("DELETE FROM movies WHERE id = :id", $id);
    }

    public static function get(array $id) {
        $movies = parent::query("SELECT id, title, director, year, runlength, description, activated, price"
                        . " FROM movies"
                        . " WHERE id = :id", $id);
        
        if (count($movies) == 1) {
            return $movies[0];
        } else {
            return null;
        }
    }

    public static function getAll() {
        return parent::query("SELECT id, title, director, year, runlength, description, activated, price"
                        . " FROM movies"
                        . " ORDER BY id ASC");
    }
    
    public static function getAllActivated() {
        $params = [
            "activated" => 1
        ];
        return parent::query("SELECT id, title, director, year, runlength, description, activated, price"
                        . " FROM movies"
                        . " WHERE activated = :activated"
                        . " ORDER BY id ASC", $params);
    }

}
