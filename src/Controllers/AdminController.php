<?php

namespace PixelVerseApp\Controllers;

use PixelVerseApp\Models\LogManager;

class AdminController extends BaseController
{
    private $logManager;

    public function __construct()
    {
        // Protection Admin : Seuls les admins et employés peuvent accéder aux logs
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'employe'])) {
            header('Location: /');
            exit;
        }
        $this->logManager = new LogManager();
    }

    /**
     * Affiche les logs d'activité NoSQL
     */
    public function logs()
    {
        $logs = $this->logManager->getAll();
        $this->render('admin/logs', [
            'title' => 'Logs d\'Activité - NoSQL',
            'logs' => $logs
        ]);
    }
}
