<?php

class Links
{
    private $tablename;
    private $connection;
    public $id;
    public $short_url;
    public $full_url;
    public $user_id;

    function __construct()
    {
        $db = new Database();
        $this->connection = $db->pdo;
        $this->tablename = 'links';
    }

    function save()
    {
        $query = "INSERT INTO {$this->tablename} (short_url, full_url, user_id) VALUES (?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        return $stmt->execute([$this->short_url,$this->full_url,$this->user_id]);
    }
}