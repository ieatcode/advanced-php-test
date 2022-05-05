<?php

namespace App\Models;

use App\System\App;

class Roster extends Model
{
    public string $table = 'roster';

    public function getThreePointShooters()
    {
        $query = "SELECT {$this->table}.name as roster_name, 
                    team.name as full_team_name, 
                    age, 
                    number as roster_number, 
                    pos as position, 
                    CONCAT(CAST((player_totals.3pt / player_totals.3pt_attempted * 100) AS DECIMAL(5,2)),'%') AS '3-pointers made %', 
                    player_totals.3pt as 'Number of 3-pointers made' 
                    FROM {$this->table} 
                    JOIN team ON {$this->table}.team_code = team.code
                    JOIN player_totals ON {$this->table}.id = player_totals.player_id
                    WHERE (player_totals.3pt / player_totals.3pt_attempted * 100) > 35";
        return $this->model->query($query);
    }

    public function getPlayerStats($search)
    {
        $where = [];
        if ($search->has('playerId')) $where[] = "roster.id = '" . $search['playerId'] . "'";
        if ($search->has('player')) $where[] = "roster.name = '" . $search['player'] . "'";
        if ($search->has('team')) $where[] = "roster.team_code = '" . $search['team'] . "'";
        if ($search->has('position')) $where[] = "roster.pos = '" . $search['position'] . "'";
        if ($search->has('country')) $where[] = "roster.nationality = '" . $search['country'] . "'";
        $where = implode(' AND ', $where);
        $sql = "SELECT roster.name, 
                player_totals.3pt as three_pt, 
                player_totals.3pt_attempted as three_pt_attempted, 
                player_totals.2pt_attempted as two_pt_attempted, 
                player_totals.2pt as two_pt, player_totals.*
            FROM player_totals
                INNER JOIN roster ON (roster.id = player_totals.player_id)
            WHERE $where";
        $data = $this->model->query($sql) ?: [];
        // calculate totals
        foreach ($data as $row) {
            unset($row->player_id);
            $row->total_points = ($row->three_pt * 3) + ($row->two_pt * 2) + $row->free_throws;
            $row->field_goals_pct = $row->field_goals_attempted ? (round($row->field_goals / $row->field_goals_attempted, 2) * 100) . '%' : 0;
            $row->three_pt_pct = $row->three_pt_attempted ? (round($row->three_pt / $row->three_pt_attempted, 2) * 100) . '%' : 0;
            $row->two_pt_pct = $row->two_pt_attempted ? (round($row->two_pt / $row->two_pt_attempted, 2) * 100) . '%' : 0;
            $row->free_throws_pct = $row->free_throws_attempted ? (round($row->free_throws / $row->free_throws_attempted, 2) * 100) . '%' : 0;
            $row->total_rebounds = $row->offensive_rebounds + $row->defensive_rebounds;
        }
        return collect($data);
    }

    public function getPlayers($search)
    {
        $where = [];
        if ($search->has('playerId')) $where[] = "roster.id = '" . $search['playerId'] . "'";
        if ($search->has('player')) $where[] = "roster.name = '" . $search['player'] . "'";
        if ($search->has('team')) $where[] = "roster.team_code = '" . $search['team'] . "'";
        if ($search->has('position')) $where[] = "roster.position = '" . $search['position'] . "'";
        if ($search->has('country')) $where[] = "roster.nationality = '" . $search['country'] . "'";
        $where = implode(' AND ', $where);
        $sql = "
            SELECT roster.*
            FROM roster
            WHERE $where";
        return collect($this->model->query($sql))
            ->map(function ($item, $key) {
                unset($item->id);
                return $item;
            });
    }
}