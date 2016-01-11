<?php 
namespace core;

if(!defined("IN_RULE")) die ("Oops");

class Controller 
{
    public $model_name;
    public $model;
    public $view_name;
    public $view;
    public $exception_message;
    function __construct()	{
        $this->view = new View();
    }
    function init()	{}
}
