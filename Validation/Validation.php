<?php
    namespace Validation;

    class Validation{
        static protected $failed = false;

        public static function make($rules = []){
            $errors = [];
            $validation_rules = require 'rules.php';

            foreach ($rules as $field => $validation){
                $validations = explode('|', $validation);
                foreach ($validations as $val){
                     $error = $validation_rules[$val]($field);
                     if($error){
                         $errors[$field][] = $error;
                     }
                }
            }

            $_SESSION['errors'] = $errors;
            if(!empty($errors)){
                static::$failed = true;
                exit(back());
            }
            return new self;
        }

        public function failed(){
            return self::$failed;
        }

    }
