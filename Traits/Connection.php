<?php
namespace Traits;
use PDO;

trait Connection{

    public static function make(){
        try{
            $db = new PDO("mysql:host={$_ENV['DB_HOST']}; dbname={$_ENV['DB_NAME']}",$_ENV['DB_USER'],$_ENV['DB_PASSWORD'],[
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        }catch (\Exception $e){
            die($e->getMessage());
        }

        return $db;
    }

}
