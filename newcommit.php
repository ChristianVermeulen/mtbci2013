<?php
require 'Connection.php';
require 'Commit.php';
require 'Repository.php';

// retreive the data and decode json
$data = json_decode($_POST['payload']);

$repository = new Repository($data->repository);

foreach($data->commits as $commit)
{
    $repository->newCommit($commit);
}


