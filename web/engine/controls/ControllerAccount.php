<?php if(!defined("IN_RULE")) die ("Oops");

class ControllerAccount extends core\Controller 
{

    function init()	{	
        $pdo = DB::getInstance()->getConnection();    	
		if (in_array($this->model_name, array("AccountLogout"))) {
			Auth::unset2FASession($pdo);
			session_destroy(); header("location: ".SITE_DIRECTORY."/account/login"); exit;
		}	

		if ($pdo == 'Connection failed') { $this->view->generate('_layout', 'exception.view','Setup DB connection.'); exit;}
		if ( is_readable("engine/model/".$this->model_name.".php") ) { 
			include_once("engine/model/".$this->model_name.".php"); 
			$this->model = new $this->model_name;
		} else { 
			$this->view_name 	= "exception.view";
			$data 				= "Missing model";
		}

		if ($_SESSION['AUTH'] == NULL)	{
			if (in_array($this->model_name, array("AccountLogin", "AccountRegister", "AccountRemind"))) {
				if ($this->model_name == "AccountLogin") 		$data = array('pageTitle'	=> 'Login');
				if ($this->model_name == "AccountRegister") 	$data = array('pageTitle'	=> 'Register');

				$data['captcha'] = $this->model->assignCaptcha($pdo);	
				$solved = 'Ok';		
				if ($data['captcha'] == 'show')  {
					$data['ReCaptchaSiteKey'] = RECAPTCHA_SITEKEY;
					if ($_SERVER['REQUEST_METHOD'] == 'POST') $solved = $this->model->verifyCaptcha(RECAPTCHA_PRIVATKEY); 
				}	
				if ($solved == 'Ok') {
					if (isset($_POST['signin'])) 	{ $data['message'] = $this->model->processLogin($_POST['email'], $pdo);  }
					if (isset($_POST['signup'])) 	{ $data['message'] = $this->model->processRegister($_POST['email'],$_FILES['image'], $pdo);  }

					// here we axpect to get auth so check auth and send to account page if ok
					if (isset($_SESSION['AUTH'])) 			{ header("location: ".Filter::pathname(SITE_DIRECTORY)."/account"); exit; }
				} else $data['message'] = 'Captcha not solved';

			} else { header("location: ".Filter::pathname(SITE_DIRECTORY)."/account/login"); exit; }
		} else {
			$User = Auth::check2FA($pdo);
			if ($_SESSION['2FA'] == 'setup') {
                if ($this->model_name != 'AccountSetup2fa') {header('location: '.Filter::pathname(SITE_DIRECTORY).'/account/setup2fa'); exit; }
                if ($_POST['createKey']) { $this->model->createImageKey($_SESSION['AUTH'], $pdo); exit; }
			}
			if ($_SESSION['2FA'] == 'no') {
                if ($this->model_name != 'Account2fa') {header('location: '.Filter::pathname(SITE_DIRECTORY).'/account/2fa'); exit; }
                if ($_FILES['image']) { 
                	$this->model->verifyImageKey($_SESSION['AUTH'], $_FILES['image'], $pdo); 
                	header("location: ".Filter::pathname(SITE_DIRECTORY)."/account"); exit;
                }                
			}
			if ($_SESSION['2FA'] == 'yes') {	


            }


		}

		$this->view->generate('_layout', $this->view_name, $data);
		$pdo = NULL;
    }

}

?>