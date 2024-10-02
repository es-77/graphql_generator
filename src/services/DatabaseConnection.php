<?php

namespace Emmanuelsaleem\Graphqlgenerator\services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use PDO;
use PDOException;

trait DatabaseConnection
{
    protected $host = '';
    protected $port = '';
    protected $database_name = '';
    protected $user_name = '';
    protected $password = '';
    protected $folder_name = '';
    protected $localhost = 'local';

    public function databaseConnection(){
        $this->host = config('database.connections.mysql.host');
        $this->port = config('database.connections.mysql.port');
        $this->database_name = config('database.connections.mysql.database');
        $this->user_name = config('database.connections.mysql.username');
        $this->password = config('database.connections.mysql.password');
        $this->folder_name = "my_custom";

        $port = $this->port;
        $database_name = $this->database_name;
        $user_name = $this->user_name;
        $password = $this->password;
        $localhost = $this->host;
        $folder_name = $this->folder_name;

        $dsn = 'mysql:host=' . $localhost . ';port=' . $port . ';dbname=' . $database_name . '';
        $username = $user_name;
        $password = $password;
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        try {
            $pdo = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            exit;
        }

        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return [
            'pdo'=>$pdo,
            'tables'=>$tables
        ];

    }
}
