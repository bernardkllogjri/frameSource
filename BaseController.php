<?php

namespace Core;
use DB;
use Traits\{Connection, Session};

class BaseController{
    public $login_url = 'admin';
    public $after_login = 'dashboard';
    protected $login_page = 'admin.login';

    use Connection;
    use Session;

    public function __construct(){
        DB::init(Connection::make());
        session_start();
    }
}
