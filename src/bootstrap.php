<?php

    error_reporting(0);

    require 'helpers.php';
    require __PROJECT_DIR__.'/config/site.php';
    require __PROJECT_DIR__.'/routes/web.php';

    (new Dotenv\Dotenv(__PROJECT_DIR__))->load();
    error_reporting(env('ERR_REPORTING'));
