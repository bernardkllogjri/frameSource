<?php

namespace eDiet;
use Dotenv\Dotenv;

define('__PROJECT_DIR__',
    dirname(
        dirname((__FILE__))
    )
);
error_reporting(getenv('ERR_REPORTING'));

/**
 * Class Bootstrap
 * @package eDiet
 */

class Bootstrap{

    /**
     * @return Bootstrap
     */
    public static function wrap(){

        $requirables = [
            dirname(__FILE__).'/helpers.php',
            __PROJECT_DIR__.'/config/site.php',
            __PROJECT_DIR__.'/routes/web.php',
        ];

        foreach($requirables as $requirable){
            if(file_exists($requirable)){
                require $requirable;
            }else{
                throw new \Error("File {$requirable} does not exist");
            }
        }

        (new Dotenv(__PROJECT_DIR__))->load();

        return new self;
    }

    /**
     * Registers defined routes and bootstraps app
     * @return mixed
     */
    public function registerRoutes(){
        return Router::handleTraffic();
    }
}
