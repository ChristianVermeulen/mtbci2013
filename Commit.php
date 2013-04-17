<?php

class Commit
{
    private $id;
    private $author;
    private $email;
    private $username;
    private $gitid;
    private $message;
    private $timestamp;
    private $url;
    private $repo;
    private $db;

    public function __construct($commit, $repo)
    {
        $db = new Connection();
        $this->db = $db->db;
        $this->repo = $repo;
        $this->loadCommit($commit);
    }

    /**
      * Load a commit from either the db or the incoming data
      */
    private function loadCommit($commit)
    {
        $query = $this->db->query("SELECT * FROM commits WHERE gitid = '".$commit->id."'");
        if($query->num_rows > 0 && $com = $query->fetch_object())
        {
            $this->id = $com->id;
            $this->author = $com->author;
            $this->email = $com->email;
            $this->username = $com->username;
            $this->gitid = $com->gitid;
            $this->message = $com->message;
            $this->timestamp = $com->timestamp;
            $this->url = $com->url;
            $this->repo = $com->repoid;
        }
        else
        {
            $this->author = $commit->author->name;
            $this->email = $commit->author->email;
            $this->username = $commit->author->username;
            $this->gitid = $commit->id;
            $this->message = $commit->message;

            $ts = getdate(strtotime($commit->timestamp));
            $this->timestamp = $ts[0];

            $this->url = $commit->url;
            var_dump("loading commit from data");
            $this->saveCommit();
        }
    }

    /**
     * Save the commit to the database by either inserting or updating it
     */
    private function saveCommit()
    {
        if($this->id == '')
        {
            $query = 'INSERT INTO commits (author, email, username, gitid, message, created, url, repoid) VALUES (
                "'.$this->author.'",
                "'.$this->email.'",
                "'.$this->username.'",
                "'.$this->gitid.'",
                "'.$this->message.'",
                "'.$this->timestamp.'",
                "'.$this->url.'",
                "'.$this->repo.'"
                )';
        }
        else
        {
            $query = 'UPDATE commits SET
                author = "'.$this->author.'",
                email = "'.$this->email.'",
                username = "'.$this->username.'",
                gitid = "'.$this->gitid.'",
                message = "'.$this->message.'",
                created = "'.$this->timestamp.'",
                url = "'.$this->url.'"
                WHERE id = '.$this->id.'
                ';
        }

        if(!$this->db->query($query))
        {
            var_dump($this->db->error);
        }
        var_dump("saved commit with: ".$query);
    }
}
