<?php
require 'Connection.php';
require 'Commit.php';
require 'Repository.php';

$db = new Connection();

$committers = array();

$result = $db->db->query("SELECT author as name, count(gitid) as score, email as email FROM commits GROUP BY username ORDER BY score DESC");

while($row = $result->fetch_object())
{
    $commiter = array(
        "name" => $row->name,
        "score" => $row->score,
        "gravatar" => "http://www.gravatar.com/avatar/".md5($row->email)."?s=80"
        );
    $committers[] = $commiter;
}

echo json_encode($committers);
