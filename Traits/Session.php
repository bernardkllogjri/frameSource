<?php
namespace Traits;

use DB;
use Validation\Validation;

trait Session{

    public function loginForm(){
        return  auth()
            ? redirect($this->after_login ?? 'dashboard')
            : view($this->login_page ?? 'login');
    }

    public function login(){
        Validation::make([
            'email' => 'required|email',
            'password' => 'required|password'
        ]);

        $user = DB::raw('SELECT * FROM users WHERE email = :email limit 1', [
            'email' => $_POST['email']
        ]);

        if(password_verify($_POST['password'],$user->password)){
            $_SESSION['user'] = $user->email;
            $_SESSION['user_name'] = $user->name;
        }else{
            $_SESSION['errors']['login'] = ['Your email and password don\'t match in our database'];
            return view($this->login_page ?? 'login');
        }
        return redirect($this->login_url ?? 'login');

    }

    public function logout(){
        if(auth()){
            session_destroy();
        }
        redirect('');
    }

}
