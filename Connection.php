<?php

class Connection
{
    public $db;

    public function __construct()
    {
        $this->db = new mysqli('localhost', 'hrprj_git', '76fvfxLe', 'hrprj_git');

        // fuck it, let's just check if you are working locally instead of dis-/enabling this line all the time
        if($_SERVER['HTTP_HOST'] !== "hrprojecten.net")
            $this->db = new mysqli('localhost', 'commits', 'mypass', 'commits');

        if(mysqli_connect_errno())
        {
            die('Fout bij verbinding: '.$mysqli->error);
        }
        return $this->db;
    }
}
