<?php

namespace App\Model;

use Exception;
use PDO;

/**
 * Class User
 * Gère les utilisateurs.
 * @package App\Model
 */
class User extends Model
{
    /**
     * @var string $table Nom de la table
     * @var int $id Identifiant de l'utilisateur
     * @var string $email Adresse e-mail de l'utilisateur
     * @var string $password Mot de passe de l'utilisateur
     */

    protected string $table = 'user';
    protected int $id;
    protected string $email = '';
    protected string $password;

    // Getter pour l'ID de l'utilisateur
    public function getId(): int
    {
        return $this->id;
    }

    // Getter pour l'adresse e-mail de l'utilisateur
    public function getEmail(): string
    {
        return $this->email;
    }

    // Getter pour le mot de passe de l'utilisateur
    public function getPassword(): ?string
    {
        return $this->password;
    }

    // Setter pour l'adresse e-mail de l'utilisateur
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    // Setter pour le mot de passe de l'utilisateur
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Insère un nouvel utilisateur dans la base de données.
     *
     * @param array $params Les paramètres de l'insertion
     *   - string $params['email'] L'adresse e-mail de l'utilisateur
     *   - string $params['password'] Le mot de passe de l'utilisateur
     *   - int $params['isAdmin'] (optionnel) Indique si l'utilisateur est un administrateur (1) ou non (0)
     */
    public function insert(array $params): void
    {
        $email = $params['email'];
        $password = $params['password'];
        $isAdmin = $params['isAdmin'] ?? 0;

        // Insérer les données dans la base de données
        try {
            $pdo = $this->connect();
            $statement = $pdo->prepare("
                INSERT INTO $this->table (email, password, isAdmin)
                VALUES (:email, :password, :isAdmin)
            ");
            $statement->execute([
                ':email' => $email,
                ':password' => $password,
                ':isAdmin' => $isAdmin
            ]);
        } catch (Exception $e) {
            echo 'Exception reçue : ',  $e->getMessage(), "\n";
            die;
        }
    }

    /**
     * Recherche un utilisateur par son adresse e-mail.
     *
     * @param string $email L'adresse e-mail de l'utilisateur à rechercher
     * @return array|null Les informations de l'utilisateur trouvées dans la base de données, ou null si aucun utilisateur n'est trouvé
     * @throws Exception En cas d'erreur lors de l'exécution de la requête
     */
    public function findByEmail(string $email): ?array
    {
        try {
            $request = $this->db->prepare("
                SELECT * 
                FROM $this->table 
                WHERE email = :email
            ");
            $request->execute([':email' => $email]);
            $result = $request->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
        return $result ?: null;
    }

    public function update(array $params): void
    {
        $id = $params['id'];
        $email = $params['email'];
        $password = $params['password'];
        $isAdmin = $params['isAdmin'] ?? 0;

        try {
            $pdo = $this->connect();
            $statement = $pdo->prepare("
            UPDATE $this->table 
            SET email = :email, password = :password, isAdmin = :isAdmin
            WHERE id = :id
        ");
            $statement->execute([
                ':id' => $id,
                ':email' => $email,
                ':password' => $password,
                ':isAdmin' => $isAdmin
            ]);
        } catch (Exception $e) {
            echo 'Exception reçue : ', $e->getMessage(), "\n";
            die;
        }
    }
}