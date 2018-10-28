<?php


class DB{
    private static $connection = null;
    const FETCH_TYPE = \PDO::FETCH_OBJ;

    public static function init($db){
        static::$connection = $db;
    }

    public static function raw($statement,$data=[]){
        try{
            $prepared_statement = static::$connection->prepare($statement);
            $prepared_statement->execute($data);
            $result = $prepared_statement->fetch(static::FETCH_TYPE);
        }catch (Exception $e){
            die($e->getMessage());
        }
        if($result) return $result;
        return [];
    }
}
