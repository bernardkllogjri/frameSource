<?php

function env($key){

    if($key == 'ERR_REPORTING'){
        switch (getenv($key)){
            case 1:
                return 32759;
                break;
            default:
                return getenv($key);
                break;
        }
    }

    return getenv($key);
}

function formated_view_string($string, array $data = []){
    extract($data);
    return "../resources/views/".str_replace('.','/',$string).".view.php";
}

function view($view, $data = []){
    extract($data);
    $errors = $_SESSION['errors'] ?? [];

    return require formated_view_string($view);
}


function dd(...$args){
    die(var_dump($args));
}

function config($key){
    $configTree = explode('.',$key);
    $configs = require "../config/{$configTree[0]}.php";
    return $configs[$configTree[1]];
}

function partials($partial){
    include formated_view_string($partial);
}

function redirect($route){
    return header("Location: ".env('BASE_URL')."/{$route}");
}

function auth(){
    return !!$_SESSION['user'];
}

function back(){
    return header("Location: {$_SERVER['HTTP_REFERER']}");
}

function errors($key=null){
    if(!isset($key)){
        return $_SESSION['errors'];
    }
    $error = $_SESSION['errors'][$key];

    if(!empty($error)){
        unset($_SESSION['errors'][$key]);
        return $error;
    }
    return [];
}
