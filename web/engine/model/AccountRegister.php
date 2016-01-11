<?php if(!defined("IN_RULE")) die ("Oops");

class AccountRegister extends core\Model 
{

	public function processRegister($user, $image, $dblink) {
		$usernew = filter_var($user, FILTER_VALIDATE_EMAIL);
		if ($usernew != FALSE) {
			if (!empty($_POST['password']) && $_POST['password'] == $_POST['password2'] && strlen($_POST['password']) > 7 ) {

				$im = new Image();
				if ($im->verifyImage($image) != 'ok') return $im->verifyImage($image);
				$imName = $im->createUploadedImage($image);
				$hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
				if ($stm = $dblink->prepare("INSERT INTO ".TABLE_USERS." (email,pass,regdate,2fa_imgname) VALUES (?,?,NOW(),?)")) {
					$stm->execute(array($usernew,$hash,$imName));
					if ($stm->errorInfo()[1] == 1062) 	{$mesg = "Email is in use." ; unlink('uploads/2fa/'.$imName);}
					if ($stm->errorInfo()[1] == 0) 		Auth::setSessionData($usernew); 
					$stm = NULL;
				} 
			} else $mesg =  "passwords are not equal or too short";
		} else $mesg =  "email used for login is not valid";
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


}
?>