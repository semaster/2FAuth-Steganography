<?php if(!defined("IN_RULE")) die ("Oops");

class Account2fa extends core\Model 
{

    public function verifyImageKey($user, $image, $dblink) {
        if ($stm = $dblink->prepare("SELECT 2fa_hash FROM  ".TABLE_USERS." WHERE email = ?")) {
            $stm->execute(array($user)); $row = $stm->fetch(); $stm = NULL;
        }   	
        $im = new Image();
        $im->checkStegoKey($image);
        $stegoKey 		= $im->stegoKey;

        if (password_verify($stegoKey, $row['2fa_hash'])) {
        	$sid    = session_id();
        	$ip 	= ip2long($_SERVER['REMOTE_ADDR']);
        	
            if ($stm = $dblink->prepare("INSERT INTO ".TABLE_SESSIONS." (timestamp,session,ip) VALUES (NOW(),?,?)")) {
                $stm->execute(array($sid, $ip)); $stm = NULL;
            } 
            
        }
		
        return $mesg;	
    }




}
?>