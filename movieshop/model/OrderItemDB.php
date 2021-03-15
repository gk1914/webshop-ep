<?php

require_once 'model/AbstractDB.php';

class OrderItemDB extends AbstractDB {
    
    public static function insert(array $params) {
        return parent::modify("INSERT INTO order_items (order_id, movie_id, amount, price)"
                        . " VALUES (:order_id, :movie_id, :amount, :price)", $params);
    }

    public static function delete(array $id) {
        
    }

    public static function get(array $id) {
        
    }

    public static function getAll() {
        return parent::query("SELECT id, order_id, movie_id, amount, price"
                . " FROM order_items"
                . " ORDER BY id ASC");
    }
    
    public static function getAllByOrderID(array $order_id) {
        return parent::query("SELECT id, order_id, movie_id, amount, price"
                . " FROM order_items"
                . " WHERE order_id = :order_id"
                . " ORDER BY id ASC", $order_id);
    }

    public static function update(array $params) {
        
    }

}