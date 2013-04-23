<?php

class Repository
{
    private $id;
    private $name;
    private $description;
    private $gitid;
    private $ownername;
    private $owneremail;
    private $url;
    private $db;

    public function __construct($repository)
    {
        $db = new Connection();
        $this->db = $db->db;
        $this->loadRepo($repository);
    }

    /**
      * Load a repo from either the db or the incoming data
      */
    private function loadRepo($repository)
    {
        if(gettype($repository) === "integer")
            $query = $this->db->query("SELECT * FROM repositories WHERE id = '".$repository."'");
        else
            $query = $this->db->query("SELECT * FROM repositories WHERE gitid = '".$repository->id."'");

        if($query->num_rows > 0 && $repo = $query->fetch_object())
        {
            $this->id = $repo->id;
            $this->name = $repo->name;
            $this->description = $repo->description;
            $this->gitid = $repo->gitid;
            $this->ownername = $repo->ownername;
            $this->owneremail = $repo->owneremail;
            $this->url = $repo->url;
        }
        else
        {
            $this->name = $repository->name;
            $this->description = $repository->description;
            $this->gitid = $repository->id;
            $this->ownername = $repository->owner->name;
            $this->owneremail = $repository->owner->email;
            $this->url = $repository->url;
            $this->saveRepo();
        }
    }

    /**
     * Save the repo to the database by either inserting or updating it
     */
    private function saveRepo()
    {
        if($this->id == '')
        {
            $query = 'INSERT INTO repositories (name, description, gitid, ownername, owneremail, url) VALUES (
                "'.$this->name.'",
                "'.$this->description.'",
                "'.$this->gitid.'",
                "'.$this->ownername.'",
                "'.$this->owneremail.'",
                "'.$this->url.'"
                )';
        }
        else
        {
            $query = 'UPDATE repositories SET
                name = '.$this->name.',
                description = '.$this->description.',
                gitid = '.$this->gitid.',
                ownername = '.$this->ownername.',
                owneremail = '.$this->owneremail.',
                url = '.$this->url.' WHERE id = '.$this->id.'
                ';
        }

        if(!$this->db->query($query))
        {
            file_put_contents("log.txt",$this->db->error, FILE_APPEND);
        }
        $this->id = $this->db->insert_id;
    }

    /**
     * Add a new repository
     */
    public function newCommit($commit)
    {
        $commit = new Commit($commit, $this->id);
    }

    /**
     * Get array of the repo
     */
    public function getRepo()
    {
        $repo = array();
        $repo['id'] = $this->id;
        $repo['name'] = $this->name;
        $repo['description'] = $this->description;
        $repo['gitid'] = $this->gitid;
        $repo['ownername'] = $this->ownername;
        $repo['owneremail'] = $this->owneremail;
        $repo['url'] = $this->url;
        return $repo;
    }
}
