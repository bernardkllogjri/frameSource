<?php

namespace Router;

class Router{
    protected static $routes = [
        'GET' => [],
        'POST' => []
    ];

    public static function handleTraffic(){

        $compounds = explode('/',
            explode('?',$_SERVER['REQUEST_URI'])[0]
        );

        for($i=0; $i < count($compounds); $i++){
            if(!$compounds[$i])
                array_splice($compounds,$i,$i+1);
        }

        $uri = '/'.implode('/',$compounds);
        $route = self::$routes[$_SERVER['REQUEST_METHOD']][$uri];

        if(empty($route)){
            return view('404');
        }

        if(!is_string($route)){
            return $route();
        }

        $class = 'Controllers\\'.explode('@',$route)[0];
        $method = explode('@',$route)[1];

        if(class_exists($class)){
            $object = (new $class);
            self::apply_middleware($object, $method);

            if(method_exists($object,$method)){
                return $object->{$method}();
            }
            throw new \Error("Method {$method} on controller {$class} does not exist");
        }

        throw new \Error("Controller {$class} does not exist");
    }

    public static function get(string $routeName, $callback){
        static::$routes['GET'][$routeName] = $callback;
    }

    public static function post(string $routeName, $callback){
        static::$routes['POST'][$routeName] = $callback;
    }

    protected static function apply_middleware($object, $method){
        if(method_exists($object, '__middleware')){

            if(!auth() && in_array($method, $object->{'__middleware'}()['auth'] ?? [])){
                return redirect($object->login_url ?? 'login');
            }else if( auth() && in_array($method, $object->{'__middleware'}()['guest'] ?? [])){
                return redirect($object->after_login);
            }

        }
    }

}
