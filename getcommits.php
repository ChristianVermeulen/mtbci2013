<?php
require 'Connection.php';
require 'Commit.php';
require 'Repository.php';

$db = new Connection();

$commits = array();

if($_REQUEST["last"] !== "0")
{
    $result = $db->db->query("SELECT * FROM commits WHERE `created` > '".$_REQUEST['last']."' ORDER BY created ASC LIMIT 0,5");
}else{
    $result = $db->db->query("SELECT * FROM commits ORDER BY created DESC LIMIT 0,20");
}

while($row = $result->fetch_object())
{
    $commit = new Commit((int)$row->id);
    $commits[] = $commit->getCommit();
}

echo json_encode($commits);
