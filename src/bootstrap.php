<?php

    const __PROJECT_DIR__ = '../../../..';
    error_reporting(0);

    require 'helpers.php';
    require __PROJECT_DIR__."/config/site.php";
    require __PROJECT_DIR__."/routes/web.php";

    (new Dotenv\Dotenv(dirname(__DIR__)))->load();
    error_reporting(env('ERR_REPORTING'));
