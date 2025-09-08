<?php
namespace controllers;
use Database\Database as DB;

class BaseController
{
    protected $DB = null;
    function __construct()
    {
         $this->DB = new DB();
    }

}