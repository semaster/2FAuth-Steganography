<?php if(!defined("IN_RULE")) die ("Oops");

class Auth 
{
    public function check2FA($dblink) {
        $_SESSION['2FA'] = 'no';
        $sid    = session_id();
        $user   = $_SESSION['AUTH'];
        $dblink->exec("DELETE FROM ".TABLE_SESSIONS." WHERE timestamp < DATE_SUB(NOW(),INTERVAL 15 MINUTE)");
        if ($stm = $dblink->prepare("SELECT COUNT(*) AS cnt FROM  ".TABLE_SESSIONS." WHERE session = ?")) {
            $stm->execute(array($sid)); $sess = $stm->fetch(); $stm = NULL;
            if ($sess['cnt'] > 0) { $_SESSION['2FA'] = 'yes'; }  
        }
        if ($stm = $dblink->prepare("SELECT 2fa_hash FROM  ".TABLE_USERS." WHERE email = ?")) {
            $stm->execute(array($user)); $row = $stm->fetch(); $stm = NULL;
            if ($row['2fa_hash'] == '') { $_SESSION['2FA'] = 'setup'; }  
        }        
        return $_SESSION['2FA'];
    }

    public function setSessionData($auth, $f2a = 'no') {
        $_SESSION['AUTH']   = $auth;
        $_SESSION['2FA']    = $f2a;
    }

    public function unset2FASession($dblink) {
        $sid    = session_id();
        $dblink->exec("DELETE FROM ".TABLE_SESSIONS." WHERE session = '$sid'");
    }
}
?>
