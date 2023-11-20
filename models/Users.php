<?php


class Users
{
    private $tablename;
    private $connection;
    public $id;
    public $name;
    public $email;
    public $password;
    public $confirmation_code;
    public $created_at;
    public $email_confirmed_at;

    function __construct()
    {
        $db = new Database();
        $this->connection = $db->pdo;
        $this->tablename = 'users';
    }

    function save()
    {
        $query = "INSERT INTO {$this->tablename} (name, email, password, confirmation_code, created_at) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        return $stmt->execute([$this->name, $this->email, $this->password, $this->confirmation_code, $this->created_at]);
    }

    function checkEmail()
    {
        $query = "SELECT id FROM users WHERE email = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([$this->email]);
        return $stmt->rowCount();
    }

    function getUserByCode()
    {
        $user = [];
        if (!empty($this->confirmation_code)) {
            $query = "SELECT * FROM users WHERE confirmation_code = ? AND email_confirmed_at IS NULL";
            $stmt = $this->connection->prepare($query);
            $stmt->execute([$this->confirmation_code]);

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $user['id'] = $row['id'];
                $user['created_at'] = $row['created_at'];
            }
        }
        return $user;
    }

    function activateUser()
    {
        $query = "UPDATE users SET email_confirmed_at = ? WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $today = date('Y-m-d H:i:s');
        return $stmt->execute([$today, $this->id]);
    }

    function validateCredentials()
    {
        $query = "SELECT * FROM users WHERE email = ? AND email_confirmed_at IS NOT NULL";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([$this->email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($user)) {
            if ($user['password'] === $this->password) {
                unset($user['password']);
                return $user;
            }
        }
        return false;
    }

    function bind($array)
    {
        foreach ($array as $key => $value) {
            $this->$key = $value;
        }
    }

    function getLinks()
    {
        $links = [];
        if (!empty($this->id)) {
            $query = "SELECT * FROM links WHERE user_id = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->execute([$this->id]);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $links[] = $row;
            }
        }
        return $links;
    }
}