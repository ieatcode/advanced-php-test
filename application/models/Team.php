<?php


namespace App\Models;

use App\System\App;

class Team extends Model
{

    public string $table = 'team';

    public function getAll()
    {
        $query = "SELECT * FROM {$this->table}";
        return $this->model->query($query);
    }

    public function getThreePointShooters()
    {
        $query = "SELECT * FROM {$this->table}";
        return $this->model->query($query);
    }

}