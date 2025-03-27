<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
class DataBase
{
    /* ================================== */
    protected $db;
    protected $pdo;
    /* DATABASE INITIALIZATION */
    function __construct($host, $user, $pass, $db)
    {
        $db = [
            'host' => $host,
            'user' => $user,
            'pass' => $pass,
            'db' => $db
        ];
        $dsn = "mysql:host={$db['host']};dbname={$db['db']};charset=utf8mb4";
        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        $this->pdo = new PDO($dsn, $db['user'], $db['pass'], $opt);
    }
    /* ================================== */

    
}
