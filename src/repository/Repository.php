<?php

require_once __DIR__.'/../../Database.php';
class Repository
{
    protected $database;

    public function __construct()
    {
        $this->getInstance();
    }

    public function getInstance()
    {
        if(!$this->database)
            $this->database = new Database();
        return $this->database;
    }


}