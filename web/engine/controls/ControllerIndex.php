<?php if(!defined("IN_RULE")) die ("Oops");

class ControllerIndex extends core\Controller 
{

	function init()	{	
		$model_path = 'engine/model/'.strtolower($this->model_name).'.php';
		if( is_readable($model_path) ) { include_once($model_path); }
		$data = array('pageTitle'	=> 'SiteMain');

		$this->view->generate('_layout', $this->view_name, $data);
	}
}

?>