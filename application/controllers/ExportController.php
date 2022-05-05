<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Roster;
use Tightenco\Collect\Support\Collection;
use App\Traits\Export;

class ExportController extends BaseController
{
    use Export;
    private array $fieldsAllowed = ['player', 'playerId', 'team', 'position', 'country'];
    private array $numberText = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
    private $format;
    private Collection $payload;
    private Roster $roster;
    private  $type;

    public function __construct()
    {
        $this->payload = collect($_REQUEST);
        $this->format = $this->payload->get('format') ?: 'html';
        $this->type = $this->payload->get('type');
        $this->roster = new Roster;
    }

    public function index()
    {
        $search = $this->payload->filter(function ($value, $key) {
            return in_array($key, $this->fieldsAllowed);
        });

        switch ($this->type) {
            case 'playerstats':
                $data = $this->roster->getPlayerStats($search);
                break;
            case 'players':
                $data = $this->roster->getPlayers($search);
                break;
            default:
                $data = [];
        }
        if (!$data) {
            exit("Error: No data found!");
        }
        echo $this->renderFormat($data);
    }
}