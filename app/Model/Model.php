<?php

namespace App\Model;

use Config\EnvironmentLoader;
use Exception;
use PDO;

/**
 * Class Model
 * @package App\Model
 */
abstract class Model
{
    /**
     * @var string $host
     * @var string $dbname
     * @var string $username
     * @var string $password
     * @var PDO $db
     * @var string $table
     * @var int $lastInsertedId
     */
    protected string $host;
    protected string $dbname;
    protected string $username;
    protected string $password = '';
    protected PDO $db;
    protected string $table;
    protected int $lastInsertedId;

    /**
     * Model constructor.
     */
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

    /**
     * Charge les variables d'environnement pour la connexion à la base de données.
     * @return void
     */
    protected function loadEnvironmentVariables(): void
    {
        $this->host = $_ENV['DB_HOST'];
        $this->dbname = $_ENV['DB_NAME'];
        $this->username = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASSWORD'];
    }

    /**
     * Établit une connexion PDO à la base de données.
     *
     * @return PDO La connexion PDO établie
     */
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

    /**
     * Recherche un utilisateur par son identifiant.
     *
     * @param int $id L'identifiant de l'utilisateur à rechercher
     * @return array|null Les informations de l'utilisateur trouvées dans la base de données, ou null si aucun utilisateur n'est trouvé
     * @throws Exception En cas d'erreur lors de l'exécution de la requête
     */
    public function findById(int $id): ?array
    {
        try {
            $pdo = $this->connect();
            $statement = $pdo->prepare("SELECT * FROM $this->table WHERE id = :id");
            $statement->execute([':id' => $id]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
        return $result ?: null;
    }

    /**
     * Récupère tous les enregistrements de la table.
     *
     * @return array Les enregistrements de la table
     */
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

    /**
     * Insère un nouvel enregistrement dans la table.
     *
     * @param array $params Les paramètres de l'insertion
     */
    abstract public function insert(array $params): void;

    /**
     * Retourne l'ID du dernier enregistrement inséré.
     *
     * @return int L'ID du dernier enregistrement inséré
     */
    public function getLastInsertedId(): int
    {
        return $this->lastInsertedId;
    }

    abstract public function update(array $params): void;
}