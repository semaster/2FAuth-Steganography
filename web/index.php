<?php 

session_start();
define("IN_RULE", TRUE);

require_once(dirname(__FILE__).'/engine/config.php');
require_once(dirname(__FILE__).'/engine/autoload.php');


$router = new core\Router;
$router->defineLanguage();
$router->run();


?>