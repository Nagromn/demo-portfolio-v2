<?php

namespace App\src\Model;

use Exception;
use PDO;

abstract class Model {
    private string $host;
    private string $dbName;
    private string $user;
    private ?string $password = '';
    protected string $table;

    public function __construct() {
        $configFilePath = dirname(__FILE__) . '/../../config/config.php';
        $config = require_once $configFilePath;

        try {
            $this->host = $config['DB_HOST'];
            $this->dbName = $config['DB_NAME'];
            $this->user = $config['DB_USER'];
            if (array_key_exists('DB_PASSWORD', $config)) {
                $this->password = $config['DB_PASSWORD'];
            }
        } catch (Exception $e) {
            echo 'Exception reçue : ',  $e->getMessage(), "\n";
            die;
        }
    }

    public function connect(): PDO
    {
        try {
            return new PDO(
                "mysql:host=$this->host;dbname=$this->dbName;charset=utf8",
                $this->user,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        }
        catch (Exception $e) {
            echo 'Exception reçue : ',  $e->getMessage(), "\n";
            die;
        }
    }

    public function findAll(): ?array
    {
        try {
            $pdo = $this->connect();
            $statement = $pdo->query("SELECT * FROM $this->table");
            return $statement->fetchAll(PDO::FETCH_ASSOC) ?? [];
        } catch (Exception $e) {
            echo 'Exception reçue : ',  $e->getMessage(), "\n";
            die;
        }
    }
}
