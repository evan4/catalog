<?php

namespace Catalog\Controllers;

use Exception;
use Catalog\Session;
use Catalog\Models\User;
use Catalog\Controllers\Controller;

class AuthController extends Controller
{
  public function login()
  {
    $postArgs = filter_input_array(INPUT_POST);
    $phone = '';
    $email = '';
    
    if(
      isset($postArgs['phone-email'])
      && !empty($postArgs['phone-email'])
    ){
      if(is_numeric($postArgs['phone-email'])){
        $phone = $this->sanitizeText( $postArgs['phone-email'] );
      }elseif($this->checkEmail($postArgs['phone-email'])){
        $email = $this->checkEmail( $postArgs['phone-email'] );
      }else{
        $this->session->set('error', 'Please fill out properly phone/email');
        redirect('/');
      }
    }else{
      $this->session->set('error', 'Please fill out phone/email');
      redirect('/');
    }

    if(
      isset($postArgs['password'])
      && !empty($postArgs['password'])
    ){
    }else{
      $this->session->set('error', 'Please fill out password');
      redirect('/');
    }

    $user = new User();

    $fieldName = 'phone';
    $fieldValue = $phone;

    if($email){
      $fieldName = 'email';
      $fieldValue = $email;
    }

    $userExists = $user->getOne(
      ['username','email', 'phone', 'password'],
      [ $fieldName => $fieldValue ]
    );

    if($userExists){
      $this->session->set('username', $userExists['username']);
      redirect('/admin');
    }else{
      $this->session->set('error', 'User with such phone or email does not exists');
      redirect('/');
    }
    
  }

  public function register()
  {
    $postArgs = filter_input_array(INPUT_POST);
    ['username' => $username, 'email' => $email, 
    'phone' => $phone] = $this->validation(filter_input_array(INPUT_POST), '/singup');

    if(
      isset($postArgs['password'])
      && !empty($postArgs['password'])
      && isset($postArgs['repeat-password'])
      && !empty($postArgs['repeat-password'])
    ){
      if( $postArgs['password'] === $postArgs['repeat-password']){

        $password = password_hash( 
          $postArgs['password'],  
          PASSWORD_DEFAULT
        );
      }else{
        $this->session->set('error', 'Confirm your password');
        redirect('/singup');
      }
      
    }else{
      $this->session->set('error', 'Please fill out your password');
      redirect('/singup');
    }

    $user = new User();

    $userExists = $user->getOne(
      ['username'],
      [ 'username' => $username ]
    );
    
    if($userExists){
      $this->session->set('error', 'User with same username is already exists');
      redirect('/singup');
    }

    $userExists = $user->getOne(
      ['email'],
      [ 'email' => $email ]
    );
    
    if($userExists){
      $this->session->set('error', 'User with same email is already exists');
      redirect('/singup');
    }

    $userExists = $user->getOne(
      ['phone'],
      [ 'phone' => $phone ]
    );
    
    if($userExists){
      $this->session->set('error', 'User with same phone is already exists');
      redirect('/singup');
    }

    try{
      $user = new User();
      $userNew = $user->saveUser([
        'username' => $username,
        'email' => $email,
        'password' => $password,
        'phone' => $phone,
      ]);
      if($userNew){
        $this->session->set('username', $username);
        redirect('/admin');
      }else{
        $this->session->set('error', $userNew);
        redirect('/singup');
      }
    }catch(Exception $e){
      $this->session->set('error', $e->getMessage());
      redirect('/singup');
    }

  }

  public function logout()
  {
      $this->session->delete('username');
      $this->session->delete('error');
      redirect('/');
  }
  
  private function check_captcha($token)
  {
    $ch = curl_init();
    $args = http_build_query([
        "secret" => $_ENV['SMARTCAPTCHA_SERVER_KEY'],
        "token" => $token,
        "ip" => $_SERVER['REMOTE_ADDR'], // Нужно передать IP-адрес пользователя.
    ]);
    curl_setopt($ch, CURLOPT_URL, "https://smartcaptcha.yandexcloud.net/validate?$args");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);
  
    $server_output = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
  
    if ($httpcode !== 200) {
        echo "Allow access due to an error: code=$httpcode; message=$server_output\n";
        return true;
    }
    $resp = json_decode($server_output);
    return $resp->status === "ok";
  }
  
}
