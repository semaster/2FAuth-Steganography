<?php if(!defined("IN_RULE")) die ("Oops");

class AccountLogin extends core\Model 
{

	public function processLogin($user, $dblink) {
		$login = filter_var($user, FILTER_VALIDATE_EMAIL);

		if ($login != FALSE) {

			if ($stm = $dblink->prepare("SELECT pass FROM ".TABLE_USERS." WHERE email=?")) {
				$stm->execute(array($login)); $row = $stm->fetch(); $stm = NULL; 
				$hash 		= $row['pass'];
			}
			if ($hash != NULL) { 
				if (password_verify($_POST['password'], $hash)) {
					Auth::setSessionData($login);
					//$this->logEnter($login, $dblink); // use if need to log user logins
				} else { $mesg =  "Wrong password";	}
			} else { $mesg =  "Wrong login"; }

		} else $mesg =  "Login is not valid";
		return $mesg;
	}

	public function assignCaptcha($dblink) {
		$user=$pass=$hash='blank'; settype($message, "string");
		$hash 			= filter_input(INPUT_COOKIE, 'PHPSESSID', FILTER_SANITIZE_SPECIAL_CHARS);
		$user 			= filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		$pass 			= filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);		
		$ip 			= $_SERVER['REMOTE_ADDR'];	$xfip = $_SERVER['HTTP_X_FORWARDED_FOR']; $xrip = $_SERVER['HTTP_X_REAL_IP'];

		$dblink->exec("DELETE FROM ".TABLE_TMPLOGS." WHERE time < DATE_SUB(NOW(),INTERVAL 15 MINUTE)");
		if ($stm = $dblink->prepare("SELECT COUNT(ip) AS cnt FROM  ".TABLE_TMPLOGS."
						WHERE time > DATE_SUB(NOW(),INTERVAL 15 MINUTE) AND (hash=? OR ip=? OR ip=?)")) {
			$stm->execute(array($hash,$ip,$xfip)); $row = $stm->fetch(); $count 	= $row['cnt'];  $stm = NULL; 
		}
		if ($count > 5 || $hash == FALSE) 		{ $out='show'; } else {	$out = 'hide'; }

		if ($stm = $dblink->prepare("INSERT INTO ".TABLE_TMPLOGS." (hash,login,pas,ip,time,message) VALUES (?,?,?,?,NOW(),?)")) {
				$stm->execute(array($hash,$user,$pass,$ip,$message)); $stm = NULL; 
		}
		
		return $out;
	}

	public function verifyCaptcha($privatekey) {
		$gRecaptchaResponse = filter_input(INPUT_POST, 'g-recaptcha-response', FILTER_SANITIZE_SPECIAL_CHARS);
		$recaptcha = new ReCaptcha\ReCaptcha($privatekey);
		$resp = $recaptcha->verify($gRecaptchaResponse, $_SERVER["REMOTE_ADDR"]);
		if ($resp->isSuccess()) $mesg = 'Ok'; else $mesg = 'No' ;  
		return $mesg;
	}

	public function logEnter ($dblink) {
		settype($time, 	"string");settype($ip, 	"string");settype($user, "string"); settype($request, "string");
		$time = time();
		$ip = $_SERVER['REMOTE_ADDR'];
		$request .= 'USER_AGENT:'.	filter_var($_SERVER['HTTP_USER_AGENT'], FILTER_SANITIZE_SPECIAL_CHARS);

		if ($stm = $dblink->prepare("INSERT INTO ".TABLE_LOGS." (usr,time,ip,options,type) VALUES (?,?,INET_ATON(?),?,'login')")) {
			$stm->execute(array($user,$time,$ip,$request));  $stm = NULL; 
		}
	}

}



?>