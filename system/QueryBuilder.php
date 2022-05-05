<?php

namespace App\System;

use PDO;

class QueryBuilder
{

    protected PDO $pdo;

    /**
     * QueryBuilder constructor.
     * @param $pdo
     */
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function query($querySQL)
    {
        $statement = $this->pdo->prepare($querySQL);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_CLASS);
    }
}