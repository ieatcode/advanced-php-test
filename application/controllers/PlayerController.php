<?php

namespace App\Controllers;

use App\Models\Roster;
use App\Models\Team;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class PlayerController
 * @package App\Controllers
 */
class PlayerController extends BaseController
{
    public function index()
    {
        $team = new Team;
        $roster = new Roster;
        $teamList = $team->getAll();
        $best3PtShooter = $roster->getThreePointShooters();
        $best3PtShooterTeam = $team->getThreePointShooters();

        try {
            view('report', [
                'team' => $teamList,
                'report1' => $best3PtShooter,
                'report2' => $best3PtShooterTeam
            ]);
        } catch (LoaderError $e) {
            dd($e->getMessage());
        } catch (RuntimeError $e) {
            dd($e->getMessage());
        } catch (SyntaxError $e) {
            dd($e->getMessage());
        }
    }
}