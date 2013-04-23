<?php

@ini_set('log_errors','On'); // enable or disable php error logging (use 'On' or 'Off')
@ini_set('display_errors','Off'); // enable or disable public display of errors (use 'On' or 'Off')
@ini_set('error_log','log.txt'); // path to server-writable log file

require 'Connection.php';
require 'Commit.php';
require 'Repository.php';

// retreive the data and decode json
$data = urldecode($_REQUEST['payload']);
$data = json_decode($data);

$file = "log.txt";
file_put_contents($file, "Payload: ".var_export($_REQUEST['payload'], true)."\r\r", FILE_APPEND);
file_put_contents($file, var_export($data->commits, true)."\r\r", FILE_APPEND);

if($data === null)
{
    file_put_contents($file, "Y U NO DECODE??\r\r", FILE_APPEND);
    echo "Y U NO DECODE?<br/>";
}

$repository = new Repository($data->repository);

foreach($data->commits as $commit)
{
    file_put_contents($file, "Received:".$commit->url."\r\r", FILE_APPEND);
    $repository->newCommit($commit);
}

file_put_contents($file, "==========================================================\r\r", FILE_APPEND);
