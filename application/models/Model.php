<?php

namespace App\Models;

use App\System\App;

class Model
{
    /**
     * @var mixed
     */
    public $model;

    public function __construct()
    {
        try {
            $this->model = App::get('database');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}