<?php
require 'Connection.php';
require 'Commit.php';
require 'Repository.php';

$db = new Connection();

$commits = array();

$result = $db->db->query("SELECT * FROM commits ORDER BY created DESC");
while($row = $result->fetch_object())
{
    $commit = new Commit((int)$row->id);
    $commits[] = $commit->getCommit();
}

echo json_encode($commits);
