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
    private $gravatar;
    private $db;

    public function __construct($commit, $repo = 0)
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
        if(gettype($commit) === "integer")
            $query = "SELECT * FROM commits WHERE  id = '".$commit."'";
        else
            $query = "SELECT * FROM commits WHERE gitid = '".$commit->id."'";


        $query = $this->db->query($query);
        if($query->num_rows > 0 && $com = $query->fetch_object())
        {
            $this->id = $com->id;
            $this->author = $com->author;
            $this->email = $com->email;
            $this->username = $com->username;
            $this->gitid = $com->gitid;
            $this->message = $com->message;
            $this->timestamp = $com->created;
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
            $this->saveCommit();
        }
        $this->gravatar = "http://www.gravatar.com/avatar/".md5($this->email)."?s=200";
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
        file_put_contents("log.txt",$query, FILE_APPEND);
        var_dump($query);
        if(!$this->db->query($query))
        {
            file_put_contents("log.txt",$this->db->error, FILE_APPEND);
        }
    }

    /**
     * Serialize the commit to send towards frontend
     */
    public function getCommit()
    {
        $commit = array();
        $commit['id'] = $this->id;
        $commit['author'] = $this->author;
        $commit['email'] = $this->email;
        $commit['username'] = $this->username;
        $commit['gitid'] = $this->gitid;
        $commit['message'] = $this->message;
        $commit['timestamp'] = $this->timestamp;
        $commit['url'] = $this->url;
        $commit['gravatar'] = $this->gravatar;
        $repo = new Repository((int)$this->repo);
        $commit['repo'] = $repo->getRepo();
        return $commit;
    }
}
