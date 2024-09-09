<?php

namespace Catalog\Controllers;

use Catalog\View;
use Catalog\Controllers\Controller;

class HomeController extends Controller
{
  public function index()
  {
      $meta = [
          'title' => 'Home'
      ];
      
      $home = new View('home@index');

      $home->render(compact('meta'));
  }

  public function singup()
  {
      $meta = [
          'title' => 'Singup'
      ];
      
      $home = new View('home@singup');

      $home->render(compact('meta'));
  }
}
