<?php

return [
    'database' => [
        'name' => 'nba2019',
        'username' => 'root',
        'password' => 'root',
        'connection' => 'mysql:host=localhost',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
  ]
];
