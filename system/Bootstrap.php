<?php

use App\System\{App, Connection, QueryBuilder};

App::bind('config', require 'config.php');

App::bind('database', new QueryBuilder(
    Connection::make(App::get('config')['database'])
));
