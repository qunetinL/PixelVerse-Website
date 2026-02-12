<?php

namespace PixelVerseApp\Controllers;

use PixelVerseApp\Models\Accessory;
use PixelVerseApp\Models\LogManager;

class AccessoryController extends BaseController
{
    private $accessoryModel;
    private $logManager;

    public function __construct()
    {
        // Protection Admin : Seuls les admins et employés peuvent gérer les accessoires
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'employe'])) {
            header('Location: /');
            exit;
        }
        $this->accessoryModel = new Accessory();
        $this->logManager = new LogManager();
    }

    /**
     * Liste des accessoires (Admin)
     */
    public function index()
    {
        $accessories = $this->accessoryModel->getAll();
        $this->render('accessories/index', [
            'title' => 'Gestion des Accessoires - PixelVerse',
            'accessories' => $accessories
        ]);
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $this->render('accessories/create', [
            'title' => 'Ajouter un Accessoire - PixelVerse'
        ]);
    }

    /**
     * Enregistre un nouvel accessoire
     */
    public function store()
    {
        $data = [
            'name' => $_POST['name'] ?? '',
            'type' => $_POST['type'] ?? 'accessoire',
            'icon' => $_POST['icon'] ?? 'fa-cube',
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        if ($this->accessoryModel->create($data)) {
            // Log de l'action
            $this->logManager->log((string) $_SESSION['user']['id'], 'creation_accessoire', [
                'name' => $data['name'],
                'type' => $data['type']
            ]);
            header('Location: /admin/accessoires?success=accessoire_cree');
            exit;
        }

        $this->render('accessories/create', [
            'title' => 'Ajouter un Accessoire - PixelVerse',
            'error' => 'Erreur lors de la création.',
            'data' => $data
        ]);
    }

    /**
     * Supprime un accessoire
     */
    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if ($id && $this->accessoryModel->delete((int) $id)) {
            // Log de l'action
            $this->logManager->log((string) $_SESSION['user']['id'], 'suppression_accessoire', [
                'id' => $id
            ]);
            header('Location: /admin/accessoires?success=accessoire_supprime');
            exit;
        }
        header('Location: /admin/accessoires?error=suppression_impossible');
        exit;
    }
}
