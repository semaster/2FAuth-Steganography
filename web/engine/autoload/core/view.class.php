<?php 
namespace core;

if(!defined("IN_RULE")) die ("Oops");

class View 
{
    public function generate ($template_view, $content_view,  $data = null) {
        if (is_array($data)) { foreach ($data as $Key=>$Value)  $$Key = $Value; }
        if (!isset($lang)) $lang = 'en';
        $template_path = 'engine/view/'.strtolower($template_view).'.php';
        if( is_readable($template_path) ) { include_once($template_path); }
    }

    private function info($param = NULL) {
        switch($param)	{  
            case 'sitehome' : $out=\Filter::pathname(SITE_DIRECTORY); break;
            case 'css_path' : $out=\Filter::pathname(SITE_DIRECTORY.CSS_PATH); break;
            case 'js_path'	: $out=\Filter::pathname(SITE_DIRECTORY.JS_PATH); break;
            default 		: $out = NULL; break;
        }
        echo $out;
    }
}
