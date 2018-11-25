<?php
namespace eDiet\Traits;
use \PDO;

trait Connection{

    public static function make(){
        try{
            $db = new PDO(
                "mysql:host=".config('site.db_host')."; dbname=".config('site.db_name'),
                    config('site.db_user'),
                    config('site.db_password'),[
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                    ]);

        }catch (\Exception $e){
            die($e->getMessage());
        }

        return $db;
    }

}
