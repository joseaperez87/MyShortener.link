<?php
/**
 * Created by PhpStorm.
 * User: josea
 * Date: 16/04/2022
 * Time: 1:09
 */

class Database
{
    public $pdo;
    private $host = 'localhost';
    private $port = '3306';
    private $user = 'root';
    private $password = '';

    function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host={$this->host};port:{$this->port};charset=utf8mb4", $this->user, $this->password);
            $this->pdo->exec('USE myshortener');
        } catch (Exception $e) {
            if(!empty($this->pdo)){
                if(file_exists(__DIR__ . "/myshortener.sql")) {
                    $sql = file_get_contents(__DIR__ . "/myshortener.sql", true);
                    try {
                        $this->pdo->prepare($sql)->execute();
                    } catch (Exception $ex) {
                        exit("Error while creating the database.");
                    }
                }
            } else
                exit("Could not connect to Database.");
        }
    }
}