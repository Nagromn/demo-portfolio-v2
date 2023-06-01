<?php

namespace App\Model;

use Config\EnvironmentLoader;
use Exception;
use PDO;

abstract class Model
{
    protected string $host;
    protected string $dbname;
    protected string $username;
    protected string $password = '';
    protected PDO $db;
    protected string $table;

    public function __construct()
    {
        EnvironmentLoader::load(__DIR__ . '/../..');
        $this->loadEnvironmentVariables();

        try {
            $this->db = $this->connect();
        } catch (Exception $e) {
            echo 'Exception reçue : ',  $e->getMessage(), "\n";
            die;
        }
    }

    protected function loadEnvironmentVariables(): void
    {
        $this->host = $_ENV['DB_HOST'];
        $this->dbname = $_ENV['DB_NAME'];
        $this->username = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASSWORD'];
    }

    public function connect(): PDO
    {
        try {
            return new PDO(
                "mysql:host=$this->host;dbname=$this->dbname;charset=utf8",
                $this->username,
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

    public function findAll(): array
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

    abstract public function insert(array $params): void;
}