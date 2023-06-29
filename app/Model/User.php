<?php

namespace App\Model;

use PDO;
use Exception;

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
     * @var string $username Pseudo de l'utilisateur
     * @var string $email Adresse e-mail de l'utilisateur
     * @var string $password Mot de passe de l'utilisateur
     * @var int $isAdmin Indique si l'utilisateur est un administrateur (1) ou non (0)
     */
    protected string $table = 'user';
    protected int $id;
    protected string $email = '';
    protected string $password;
    protected string $username;
    protected ?int $isAdmin = 0;

    /**
     * Insère un nouvel utilisateur dans la base de données.
     * @return void
     * @throws Exception En cas d'erreur lors de l'exécution de la requête
     */
    public function insert(): void
    {
        // Insérer les données dans la base de données
        try {
            // Préparation de la requête d'insertion dans la base de données
            $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (username, email, password, isAdmin) VALUES (:username, :email, :password, :isAdmin)');
            $query->bindValue(':username', $this->getUsername());
            $query->bindValue(':email', $this->getEmail());
            $query->bindValue(':password', $this->getPassword());
            $query->bindValue(':isAdmin', $this->getIsAdmin());
            $query->execute(); // Exécute la requête d'insertion dans la base de données
        } catch (Exception $e) {
            echo 'Exception reçue : ', $e->getMessage(), "\n";
        }
    }

    /**
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
            echo 'Exception reçue : ', $e->getMessage(), "\n";
        }
        return $result ?: null; // Retourne l'email de l'utilisateur ou null si aucun email n'est trouvé
    }

    /**
     * Permet de mettre à jour les informations d'un utilisateur dans la base de données.
     * @param array $params
     * @return void
     */
    public function update(array $params): void
    {
        $id = $params['id'] ?? null; // Récupère l'id de l'utilisateur
        $email = $params['email'] ?? null; // Récupère l'email de l'utilisateur
        $password = $params['password'] ?? null; // Récupère le mot de passe de l'utilisateur

        try {
            $request = $this->db->prepare("
            UPDATE $this->table
            SET email = :email, password = :password, isAdmin = :isAdmin
            WHERE id = :id
        ");
            $request->execute([
                ':id' => $id,
                ':email' => $email,
                ':password' => $password,
            ]);
        } catch (Exception $e) {
            echo 'Exception reçue : ', $e->getMessage(), "\n";
        }
    }

    /**
     * Supprime un utilisateur de la base de données.
     * @return void
     * @throws Exception En cas d'erreur lors de l'exécution de la requête
     */
    public function delete(): void
    {
        try {
            $request = $this->db->prepare("
                DELETE FROM $this->table
                WHERE id = :id
            ");
            $request->execute([':id' => $this->getId()]);
        } catch (Exception $e) {
            echo 'Exception reçue : ', $e->getMessage(), "\n";
        }
    }

    /**
     * @return int L'identifiant de l'utilisateur
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string Le pseudo de l'utilisateur
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string L'adresse e-mail de l'utilisateur
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string|null Le mot de passe de l'utilisateur
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return int|null Indique si l'utilisateur est un administrateur (1) ou non (0)
     */
    public function getIsAdmin(): ?int
    {
        return $this->isAdmin;
    }

    /**
     * @param string $username
     * @return void Le pseudo de l'utilisateur
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @param string $email
     * @return void L'adresse e-mail de l'utilisateur
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param string $password
     * @return void Le mot de passe de l'utilisateur
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @param int $isAdmin
     * @return void Indique si l'utilisateur est un administrateur (1) ou non (0)
     */
    public function setIsAdmin(int $isAdmin): void
    {
        $this->isAdmin = $isAdmin;
    }
}