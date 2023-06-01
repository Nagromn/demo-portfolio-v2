<?php

namespace App\Model;

use Exception;

require_once __DIR__ . '/Model.php';
class User extends Model
{
    protected string $table = 'user';

    public function insert(array $params): void
    {
        $username = $params['username'];
        $email = $params['email'];
        $password = $params['password'];
        $isAdmin = $params['isAdmin'] ?? 0;

        // Insérer les données dans la base de données
        try {
            $pdo = $this->connect();
            $statement = $pdo->prepare("
                INSERT INTO $this->table (username, email, password, createdAt, isAdmin)
                VALUES (:username, :email, :password, NOW(), :isAdmin)
            ");
            $statement->execute([
                ':username' => $username,
                ':email' => $email,
                ':password' => $password,
                ':isAdmin' => $isAdmin
            ]);
        } catch (Exception $e) {
            echo 'Exception reçue : ',  $e->getMessage(), "\n";
            die;
        }
    }
}