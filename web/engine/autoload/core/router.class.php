<?php 
namespace core;

if(!defined("IN_RULE")) die ("Oops");

class Router 
{
    private $route = array("controller"=>"ControllerIndex","model"=>"IndexModel","view"=>"IndexModel.view");
    private $locales = array("en"=>"en_US.utf8","ru"=>"ru_RU.utf8");
    private	$language;

    public function __construct() {
        $this->parseURI();		
    }

    public function addRoute() {

    }

    public function run() {
        $control_path = 'engine/controls/'.$this->route['controller'].'.php';
        if ( is_readable($control_path) ) { include_once($control_path); settype($msg, 	"string");} else { 
            include_once('engine/controls/ControllerException.php');
            $this->route['controller'] 	= "ControllerException"; 
            $this->route['model']		= "exception";
            $this->route['view'] 		= "exception.view";
            $msg						= "Route to controller not found";
        }

        $controller = new $this->route['controller'];
        $controller->model_name 		= $this->route['model'];
        $controller->view_name 			= $this->route['view'];
        $controller->exception_message 	= $msg;

        if ( method_exists($controller, 'init') ) { $controller->init(); } 
    }

    public function defineLanguage() {
        setlocale (LC_MESSAGES, $this->locales[$this->language]);
        bindtextdomain 	($this->route['view'], 'language'); 
        textdomain 		($this->route['view']);
        bind_textdomain_codeset ( $this->route['view'], 'UTF-8' );
    }

    private function parseURI () {
        $request = explode('/', str_replace(SITE_DIRECTORY, "", $_SERVER['QUERY_STRING']));
        if ( in_array($request[1], array('en','ru')) ) {
            $this->language 	= $request[1];
            $route_controller 	= $request[2];
            $route_model 		= $request[3];
        } else {
            $this->language 	= 'en';
            $route_controller 	= $request[1];
            $route_model 		= $request[2];		
        }

        if ( $control_name = filter_var($route_controller, FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^([a-zA-Z0-9])+$/"))) ) {	
            $this->route['controller'] = 'Controller'.ucfirst(strtolower($control_name));
        } 
        if ( $model_name = filter_var($route_model, FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^([a-zA-Z0-9])+$/"))) ) {
            $model_name = ucfirst(strtolower($model_name));
        } else {$model_name = 'Index';}
        if ($this->route['controller'] !== 'ControllerIndex') {
            $this->route['model'] = ucfirst($control_name).ucfirst($model_name);
            $this->route['view'] = $this->route['model'].'.view';
        } 		
    }
}
?>

