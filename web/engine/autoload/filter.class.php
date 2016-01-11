<?php if(!defined("IN_RULE")) die ("Oops");

class Filter 
{
    public function pathname($param) {
        return filter_var($param, FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^([a-zA-Z0-9_\/])+$/")));
    }
}
?>