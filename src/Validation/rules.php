<?php
    return [
        'required' => function($field){
            if(empty($_POST[$field])) {
                return ucfirst($field) . ' is required';
            }
        },
        'email' => function($field){
            if(!preg_match('/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/',
                $_POST[$field])){
                return ucfirst($field) . ' is not a valid email';
            }
        },
        'password' => function($field){
            if(strlen($_POST[$field]) < 6){
                return ucfirst($field) . ' should be at least 6 characters long';
            }
        }
    ];
