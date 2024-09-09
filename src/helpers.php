<?php
use Catalog\Session;

function redirect($url) {
  header('Location: ' . $url, true, 302);
  die();
}

function getUsername() 	{
  $session = new Session();
  $session->startSession();

  return $session->get('username');
}
