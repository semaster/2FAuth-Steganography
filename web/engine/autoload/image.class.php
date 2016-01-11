<?php if(!defined("IN_RULE")) die ("Oops");

class Image 
{
    private $allowed_ext;
    private $allowed_mime;
    private $current_ext;
    private $createFunc;
    private $saveFunc;

    /** StegoKey embeded in image
     * @var string 
     */
    public $stegoKey;

    public function __construct() {
        $this->allowed_ext = array('image/png'=>'png','image/gif'=>'gif','image/jpeg'=>'jpg');
        $this->allowed_mime = array('image/png','image/gif','image/jpeg');
    }

    public function checkStegoKey($file) {
        $channels = array('red', 'green', 'blue');
        $this->stegoKey = '';
        $this->verifyImage($file);
        $this->defineCSFunctions($this->current_ext);
        $image = call_user_func($this->createFunc, $file['tmp_name']);

        for($x=0; $x<imagesx($image); $x++) {
            for($y=0; $y<imagesy($image); $y++) {
                $pixel_index = imagecolorat($image, $x, $y);
                $RGB = imagecolorsforindex($image, $pixel_index);

                foreach ($channels as $channel) {
                    // get channel and read least significant bit
                    $this->stegoKey .= ($RGB[$channel] & 1) ? '1' : '0';
                }
            }
        }
    }

    public function embedStegoKey($image) {
        $channels = array('red', 'green', 'blue');
        $w = imagesx($image);  
        $h = imagesy($image);
        imagealphablending($image, false);
        for($x=0; $x<$w; $x++) {
            for($y=0; $y<$h; $y++) {
                $pixel_index = imagecolorat($image, $x, $y);
                $RGB = imagecolorsforindex($image, $pixel_index);

                foreach ($channels as $channel) {
                    // get channel and modify bit
                    $embed = rand(0,1); $embed = (string)$embed;
                    if ($RGB[$channel] & 1) {   // odd
                        $RGB[$channel] = ($embed === '1') ? $RGB[$channel] : $RGB[$channel] - 1;  
                    } else {                    // even
                        $RGB[$channel] = ($embed === '1') ? $RGB[$channel] + 1 : $RGB[$channel]; 
                    }
                    $this->stegoKey .= $embed; 
                }
                $stg_pixel = imagecolorallocatealpha($image, $RGB['red'], $RGB['green'], $RGB['blue'], $RGB['alpha']);
                imagesetpixel($image, $x,$y, $stg_pixel);
            }
        }
        //imagesavealpha($image, true);
        return $image;
    }
    
    public function createUploadedImage($image) {
        $this->verifyImage($image);
        $this->defineCSFunctions($this->current_ext);
        $imtosave = call_user_func($this->createFunc, $image['tmp_name']);
        imagesavealpha($imtosave, true);
        $saveName = $this->getRandomFileName('uploads/2fa',$this->current_ext);
        $savePathName = $_SERVER['DOCUMENT_ROOT'].Filter::pathname(SITE_DIRECTORY).'/uploads/2fa/'.$saveName;
        $out = call_user_func_array($this->saveFunc, array($imtosave, $savePathName));
        imagedestroy($imtosave);
        $saveName = ($out ? $saveName : $out);
        return $saveName;
    }

    public function loadLocalFile($localFile) {
        $loadExt = substr(strrchr($localFile,'.'),1);
        $this->defineCSFunctions($loadExt);
        $imtoload = call_user_func($this->createFunc, $localFile);
        imagesavealpha($imtoload, true);
        return $imtoload;
    }

    public function getRandomFileName($path, $extension='') {
        $extension = $extension ? '.' . $extension : '.png';
        $path = $path ? $path . '/' : '';
         do {
            $name = uniqid('', true);
            $file = $path . $name . $extension;
        } while (file_exists($file));
        return $name.$extension;
    }

    public function verifyImage($image) {
        $out = 'ok';
        if ($this->gdversion() == false) return 'GD not found';	
        if ($image['size'] > 262144) $out = 'File size is too big.';
        $info = getimagesize($image['tmp_name']);
        $mime = (is_array($info) && array_key_exists('mime', $info) ? $info['mime'] : null);
        if (in_array($mime, $this->allowed_mime)) { 
            $this->current_ext = strtolower($this->allowed_ext[$mime]); 
        } else $out = 'Wrong image type';
        return $out;
    }

    public function gdversion($full = false) {
        static $gd_version = null;
        static $gd_full_version = null;
        if ($gd_version === null) {
            if (function_exists('gd_info')) {
                $gd = gd_info();
                $gd = $gd["GD Version"];
                $regex = "/([\d\.]+)/i";
            } else {
                ob_start();
                phpinfo(8);
                $gd = ob_get_contents();
                ob_end_clean();
                $regex = "/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i";
            }
            if (preg_match($regex, $gd, $m)) {
                $gd_full_version = (string) $m[1];
                $gd_version = (float) $m[1];
            } else {
                $gd_full_version = 'none';
                $gd_version = 0;
            }
        }
        if ($full) {
            return $gd_full_version;
        } else {
            return $gd_version;
        }
    }
    private function defineCSFunctions($ext) {
        switch($ext) {
            case "png": 
                $this->createFunc = 'imagecreatefrompng';     
                $this->saveFunc = 'imagepng'; 
                break;
            case "jpg": 
                $this->createFunc = 'imagecreatefromjpeg';    
                $this->saveFunc = 'imagejpeg'; 
                break;
            case "gif": 
                $this->createFunc = 'imagecreatefromgif';     
                $this->saveFunc = 'imagegif'; 
                break;
            default: 
                $this->createFunc = 'imagecreatefromstring';  
                $this->saveFunc = 'imagepng'; 
                break;
        }
    }
}