<?php

require_once 'model/AbstractDB.php';

class UserDB extends AbstractDB {

    public static function insert(array $params) {
        return parent::modify("INSERT INTO users (name, surname, email, phone, post_adress_zipcode, activated, type, password) "
                        . " VALUES (:name, :surname, :email, :phone, :post_adress_zipcode, :activated, :type, :password)", $params);
    }
    
    public static function insertCustomer(array $params) {
        $params["password"] = password_hash($params["password"], PASSWORD_DEFAULT);
        return parent::modify("INSERT INTO users (name, surname, email, phone, post_adress_zipcode, adress, type, password) "
                        . " VALUES (:name, :surname, :email, :phone, :post_adress_zipcode, :adress, :type, :password)", $params);
    }
    
    public static function insertSalesman(array $params) {
        $params["password"] = password_hash($params["password"], PASSWORD_DEFAULT);
        return parent::modify("INSERT INTO users (name, surname, email, type, password) "
                        . " VALUES (:name, :surname, :email, :type, :password)", $params);
    }
    
    public static function update(array $params) {}
    
    public static function updateCustomer(array $params) {
        return parent::modify("UPDATE users SET name = :name, surname = :surname, email = :email, phone = :phone, "
                        . "post_adress_zipcode = :post_adress_zipcode, adress = :adress, activated = :activated"
                        . " WHERE id = :id", $params);
    }
    
    public static function updateEmployee(array $params) {
        return parent::modify("UPDATE users SET name = :name, surname = :surname, email = :email, activated = :activated"
                        . " WHERE id = :id", $params);
    }
    
    public static function updatePassword(array $params) {
        $params["password"] = password_hash($params["password"], PASSWORD_DEFAULT);
        return parent::modify("UPDATE users SET password = :password"
                        . " WHERE id = :id", $params);
    }

    public static function delete(array $id) {
        return parent::modify("DELETE FROM users WHERE id = :id", $id);
    }

    public static function get(array $id) {
        $users = parent::query("SELECT id, name, surname, email, phone, post_adress_zipcode, adress, activated, type, password"
                        . " FROM users"
                        . " WHERE id = :id", $id);
        
        if (count($users) == 1) {
            return $users[0];
        } else {
            return null;
        }
    }
    
    public static function getByEmail(array $email) {
        $users = parent::query("SELECT id, name, surname, email, phone, post_adress_zipcode, adress, activated, type, password"
                        . " FROM users"
                        . " WHERE email = :email", $email);
        
        if (count($users) == 1) {
            return $users[0];
        } else {
            return null;
        }
    }

    public static function getAll() {
        return parent::query("SELECT id, name, surname, email, phone, post_adress_zipcode, adress, activated, type, password"
                        . " FROM users"
                        . " ORDER BY id ASC");
    }
    
    public static function getAllByType(array $type) {
        return parent::query("SELECT id, name, surname, email, phone, post_adress_zipcode, adress, activated, type, password"
                        . " FROM users"
                        . " WHERE type = :type"
                        . " ORDER BY id ASC", $type);
    }
    
    public static function getAllAuthorized() {
        $authorizedUsers = parent::query("SELECT email"
                        . " FROM users"
                        . " WHERE type = 1 OR type = 2"
                        . " ORDER BY id ASC");
        
        $authorizedEmails = array();
        foreach ($authorizedUsers as $user) {
            array_push($authorizedEmails, $user["email"]);
        }
        return $authorizedEmails;
    }
    
    public static function getCity(array $zipcode) {
        $city = parent::query("SELECT city"
                        . " FROM post_adress"
                        . " WHERE zipcode = :zipcode", $zipcode);
        if (count($city) == 1) {
            return $city[0]["city"];
        } else {
            return null;
        }
    }
    
    public static function checkEmail(array $email) {
        return parent::query("SELECT email"
                        . " FROM users"
                        . " WHERE email = :email", $email);
    }

}
