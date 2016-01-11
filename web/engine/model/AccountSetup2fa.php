<?php if(!defined("IN_RULE")) die ("Oops");

class AccountSetup2fa extends core\Model 
{

    public function createImageKey($user, $dblink) {
        if ($stm = $dblink->prepare("SELECT 2fa_imgname FROM  ".TABLE_USERS." WHERE email = ?")) {
            $stm->execute(array($user)); $row = $stm->fetch(); $stm = NULL;
            $file = 'uploads/2fa/'.$row['2fa_imgname']; 
        }
        
        $im = new Image();
        $imageclean 	= $im->loadLocalFile($file);
        $imagekey 		= $im->embedStegoKey($imageclean);
        $stegoKey 		= $im->stegoKey;
        $hash = password_hash($stegoKey, PASSWORD_DEFAULT);

        if ($stm = $dblink->prepare("UPDATE ".TABLE_USERS." SET 2fa_hash = ? WHERE email = ?")) {
            $stm->execute(array($hash, $user));  $stm = NULL;
        }

        if (ob_get_level()) {ob_end_clean();}
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=KeyImage.png');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        //header('Content-Length: ' . filesize($file));
        $ok = imagepng($imagekey); //, NULL, 9       
        imagedestroy($imagekey); 

        return $ok;
        
    }


}
?>