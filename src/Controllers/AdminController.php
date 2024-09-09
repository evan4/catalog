<?php

namespace Catalog\Controllers;

use Catalog\View;
use Catalog\Session;
use Catalog\Models\User;

class AdminController extends Controller
{
  public $session;

  public function __construct()
  {
      $this->session = new Session();
      $this->session->startSession();
      if(!$this->session->exists('username')) redirect('/');
  }
  
  public function index()
  {
      $meta = [
          'title' => 'Admin'
      ];

      $username = $this->session->get('username');

      $user = new User();

      $currentUser = $user->getOne(
        ['id', 'username', 'email', 'phone'],
        [ 'username' => $username ]
      );
      $home = new View('admin@index');
      
      $home->render(compact('meta', 'currentUser'));
  }

  public function updateUser()
  {
    $postArgs = filter_input_array(INPUT_POST);
    $id = $postArgs['id'];

    if(
      isset($postArgs['id'])
      && $this->isInteger($id)){
      $id = (int) $postArgs['id'];
    }else{
      $this->session->set('error', 'There is an error in user data. Please refresh page or logout.'.$postArgs['id']);
      redirect('/admin');
    }
    
    ['username' => $username, 'email' => $email, 
      'phone' => $phone] = $this->validation(filter_input_array(INPUT_POST), '/admin');

    $user = new User();
    
    $currentUser = $user->getOne(
      ['username', 'email', 'phone'],
      [ 'id' => $postArgs['id'] ]
    );

    if(!$currentUser){
      $this->session->set('error', 'There is an error occur. Please login again');
      $this->session->delete('username');
      $this->session->delete('error');
      redirect('/');
    }

    $updateUserData = [];

    if($currentUser['username'] !== $username){
      $userExists = $user->getOne(
        ['username'],
        [ 'username' => $username ]
      );
      
      if($userExists){
        $this->session->set('error', 'User with same username is already exists');
        redirect('/admin');
      }
      $updateUserData['username'] = $username;
    }else{
      $username = '';
    }

    if($currentUser['email'] !== $email){
      $userExists = $user->getOne(
        ['username', 'email', 'phone'],
        [ 'email' => $email ]
      );
      
      if($userExists){
        $this->session->set('error', 'User with same email is already exists');
        redirect('/admin');
      }
      $updateUserData['email'] = $email;
    }else{
      $email = '';
    }

    if($currentUser['phone'] !== $phone){
      $userExists = $user->getOne(
        ['username', 'email', 'phone'],
        [ 'phone' => $phone ]
      );
      
      if($userExists){
        $this->session->set('error', 'User with same phone is already exists');
        redirect('/admin');
      }
      $updateUserData['phone'] = $phone;
    }else{
      $phone = '';
    }

    if(
      isset($postArgs['password'])
      && !empty($postArgs['password'])
    ){
      $updateUserData['password'] = password_hash( 
        $postArgs['password'],  
        PASSWORD_DEFAULT
      );
    }

    $user = new User();
    $userUpdate = $user->updateUser($updateUserData, $id);
    
    if($userUpdate){
      $this->session->set('success', 'Your profile was updated!');
      if($updateUserData['password']){
        redirect('/');
      }elseif($updateUserData['username']){
        $this->session->set('username', $updateUserData['username']);
      }
      redirect('/admin');
    }else{
      $this->session->set('error', 'There was an error occur while updating your profile. Please try again later.');
      redirect('/admin');
    }
    
  }
}
