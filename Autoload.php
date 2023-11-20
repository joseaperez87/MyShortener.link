<?php
/**
 * Created by PhpStorm.
 * User: josea
 * Date: 16/04/2022
 * Time: 1:46
 */
session_start();
class Autoload
{
    private $filePath;

    function __construct()
    {

        $this->filePath = realpath(dirname(__FILE__));

        spl_autoload_register(array($this, "database"));
        spl_autoload_register(array($this, "models"));
        spl_autoload_register(array($this, "api"));

    }


    public function database($class)
    {

        $controllerDir = $this->filePath . "/libs/";

        if (file_exists($controllerDir . $class . ".php")) {

            require_once $controllerDir . $class . ".php";

        }

    }

    public function models($class)
    {

        $controllerDir = $this->filePath . "/models/";

        if (file_exists($controllerDir . $class . ".php")) {

            require_once $controllerDir . $class . ".php";

        }

    }
    public function api($class)
    {
        $controllerDir = $this->filePath . "/api/";
        if (file_exists($controllerDir . $class . ".php")) {

            require_once $controllerDir . $class . ".php";

        }

    }
}
new Autoload();

function generateShortUrl()
{
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $len = rand(4, 12);
    return substr(str_shuffle($permitted_chars), 0, $len);
}

function generateCode()
{
    $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    return substr(str_shuffle($permitted_chars), 0, 6);
}

function sendConfirmationEmail($email, $code)
{
    $to = $email;
    $subject = 'Confirmation code';
    $body = 'This is your confirmation code: ' . $code;
    $from = 'joseaperez87@gmail.com';

    if (mail($to, $subject, $body, $from)) {
        return true;
    }
    return false;
}