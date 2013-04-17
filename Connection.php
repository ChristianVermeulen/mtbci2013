<?php

class Connection
{
    public $db;

    public function __construct()
    {
        $this->db = new mysqli('localhost', 'hrprj_git', '76fvfxLe', 'hrprj_git');
        if(mysqli_connect_errno())
        {
            die('Fout bij verbinding: '.$mysqli->error);
        }
        return $this->db;
    }
}
