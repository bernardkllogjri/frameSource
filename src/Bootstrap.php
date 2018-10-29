<?php

namespace FrameLab;

use Dotenv\Dotenv;

class Bootstrap{

    public static function wrap(){

        $requirables = [
            'helpers.php',
            __PROJECT_DIR__.'/config/site.php',
            __PROJECT_DIR__.'/routes/web.php',
            __PROJECT_DIR__.'/vendor/autoload.php'
        ];

        foreach($requirables as $requirable){
            if(file_exists($requirable)){
                require $requirable;
            }else{
                throw new \Error("File {$requirable} does not exist");
            }
        }

        (new Dotenv(__PROJECT_DIR__))->load();
        error_reporting(env('ERR_REPORTING'));

        return new self;
    }

    public function registerRoutes(){
        return Router::handleTraffic();
    }
}
