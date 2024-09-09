<?php

namespace Catalog\Controllers;

use Catalog\Session;

class Controller
{
  public $session;

  public function __construct()
  {
      $this->session = new Session();
      $this->session->startSession();
  }

  public function sanitizeText(string $text): string
  {
      return filter_var( $text, FILTER_SANITIZE_SPECIAL_CHARS);
  }

  public function isInteger($int, int $min = 1): bool
  {
    return (filter_var($int, FILTER_VALIDATE_INT, array("options" => array("min_range"=>$min))) !== false);
  }
    
  public function checkEmail(string $email): string | null
  {
      $filteredEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
      
      if ($filteredEmail) {
          return filter_var($filteredEmail, FILTER_SANITIZE_EMAIL);
      }

      return null;
  }

  public function validation(array $postArgs, string $redirectUrl)
  {
    $userData = [];
    if(
      isset($postArgs['username'])
      && !empty($postArgs['username'])
    ){
      $userData['username'] = $this->sanitizeText( $postArgs['username'] );
    }else{
      $this->session->set('error', 'Please fill out your name');
      redirect($redirectUrl);
    }

    if(
      isset($postArgs['email'])
      && !empty($postArgs['email'])
    ){
      if($this->checkEmail($postArgs['email'])){
        $userData['email'] = $this->sanitizeText( $postArgs['email'] );
      }else{
        $this->session->set('error', 'Email address is not correct');
        redirect($redirectUrl);
      }
      
    }else{
      $this->session->set('error', 'Please fill out your email');
      redirect($redirectUrl);
    }

    if(
      isset($postArgs['phone'])
      && !empty($postArgs['phone'])
    ){
      $userData['phone'] = $this->sanitizeText( $postArgs['phone'] );
    }else{
      $this->session->set('error', 'Please fill out your phone');
      redirect($redirectUrl);
    }

    return $userData;
  }

}
