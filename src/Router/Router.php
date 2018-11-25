<?php

namespace eDiet;

class Router{
    protected static $routes = [
        'GET' => [],
        'POST' => []
    ];

    private $wildcard, $controller, $method = null;

    /**
     * @return mixed
     * Handle the traffic coming from the registered routes
     */

    public static function handleTraffic(){

        $compounds = explode('/',
            explode('?',$_SERVER['REQUEST_URI'])[0]
        );

        for($i=0; $i < count($compounds); $i++){
            if(!$compounds[$i]) {
                array_splice($compounds, $i, $i + 1);
            }
        }

        return static::prepareRoute($compounds)->applyMiddleware()->direct();
    }

    /**
     * @param string $routeName
     * @param $callback
     * Register incoming GET requests
     */

    public static function get(string $routeName, $callback){
        static::$routes['GET'][$routeName] = $callback;
    }

    /**
     * @param string $routeName
     * @param $callback
     * Register incoming POST requests
     */

    public static function post(string $routeName, $callback){
        static::$routes['POST'][$routeName] = $callback;
    }

    /**
     * @return $this
     * Applying middleware to the registered methods if there are any
     */

    protected function applyMiddleware(){

        if(method_exists($this->controller, '__middleware')){

            if(!auth() && in_array($this->method, $this->controller->{'__middleware'}()['auth'] ?? [])){
                return redirect($this->controller->login_url ?? 'login');
            }else if( auth() && in_array($this->method, $this->controller->{'__middleware'}()['guest'] ?? [])){
                return redirect($this->controller->after_login);
            }

        }
        return $this;
    }

    /**
     * @param string $wildCard
     * @param $request
     * @return $this
     *
     * Generates wild card if registered in the route
     */

    protected function execute($request, string $wildCard = null){
        $this->wildcard = $wildCard;
        if(!is_string($request)){
            return $wildCard ? $request($wildCard) : $request();
        }
        $class = 'Controllers\\'.explode('@',$request)[0];

        if(!class_exists($class)){
            throw new \Error("Controller {$class} does not exist");
        }

        $this->controller = new $class;
        $this->method = explode('@', $request)[1];

        return $this;

    }

    /**
     * @return mixed
     */

    public function direct(){
        if(method_exists($this->controller,$this->method)){
            return $this->wildcard ?
                $this->controller->{$this->method}($this->wildcard) :
                $this->controller->{$this->method}();
        }
        throw new \Error("Method {$this->method} on controller {$this->controller} does not exist");
    }

    /**
     * @param array $compounds
     * @return Router
     */

    protected static function prepareRoute(array $compounds){
        $uri = '/'.implode('/',$compounds);
        $total = count($compounds);
        $route = self::$routes[$_SERVER['REQUEST_METHOD']][$uri];
        $arg = null;

        if(!$route){
            $arg = $compounds[$total-1];
            array_splice($compounds, $total-1, $total);
            $uri = '/'.implode('/',$compounds);

            $routeMatches = array_filter(self::$routes[$_SERVER['REQUEST_METHOD']],function ($method, $registeredRoute) use ($uri){
                return $uri == substr($registeredRoute,0,strpos($registeredRoute,"{")-1);
            },ARRAY_FILTER_USE_BOTH);


            if(!count($routeMatches)){
                die(view('404'));
            }

        }
        return (new self)->execute($route ?? array_values($routeMatches)[0],$arg);

    }

}
