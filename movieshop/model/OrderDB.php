<?php

require_once 'model/AbstractDB.php';

class OrderDB extends AbstractDB {
    
    public static function insert(array $params) {
        return parent::modify("INSERT INTO orders (user_id, time_of_order, price_total, state)"
                        . " VALUES (:user_id, :time_of_order, :price_total, :state)", $params);
    }

    public static function delete(array $id) {
        
    }

    public static function get(array $id) {
        $orders = parent::query("SELECT id, user_id, time_of_order, price_total, state"
                        . " FROM orders"
                        . " WHERE id = :id", $id);
        if (count($orders) == 1) {
            return $orders[0];
        } else {
            return null;
        }
    }

    public static function getAll() {
        return parent::query("SELECT id, user_id, time_of_order, price_total, state"
                        . " FROM orders"
                        . " ORDER BY id ASC");
    }
    
    public static function getAllByState(array $state) {
        return parent::query("SELECT id, user_id, time_of_order, price_total, state"
                        . " FROM orders"
                        . " WHERE state = :state"
                        . " ORDER BY id ASC", $state);
    }
    
    public static function getAllByUser(array $user_id) {
        return parent::query("SELECT id, user_id, time_of_order, price_total, state"
                        . " FROM orders"
                        . " WHERE user_id = :user_id"
                        . " ORDER BY id ASC", $user_id);
    }

    public static function update(array $params) {
        
    }
    
    public static function updateState(array $params) {
        return parent::modify("UPDATE orders SET state = :state"
                        . " WHERE id = :id", $params);
    }

}