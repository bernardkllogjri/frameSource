<?php

    error_reporting(0);

    require '../vendor/autoload.php';
    require 'helpers.php';
    require '../config/site.php';
    require '../routes/web.php';

    (new Dotenv\Dotenv(dirname(__DIR__)))->load();
    error_reporting(env('ERR_REPORTING'));
