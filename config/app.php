<?php
define("ROOT", dirname(__DIR__));
define("WWW", ROOT . '/public');
define("APP", ROOT . '/src');
define("VIEWS", ROOT . '/resources/views/');
define("CONF", ROOT . '/config/');

require_once __DIR__.'/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();
define("SMARTCAPTCHA_CLIENT_KEY", $_ENV['SMARTCAPTCHA_CLIENT_KEY']);
define("SMARTCAPTCHA_SERVER_KEY", $_ENV['SMARTCAPTCHA_SERVER_KEY']);
