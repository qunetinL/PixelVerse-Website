<?php

namespace PixelVerseApp\Controllers;

use PixelVerseApp\Models\Accessory;

class AccessoryController extends BaseController
{
    private $accessoryModel;

    public function __construct()
    {
        // Protection : l'utilisateur doit être admin ou employe
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'employe'])) {
            header('Location: /connexion');
            exit;
        }
        $this->accessoryModel = new Accessory();
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
            header('Location: /admin/accessoires?success=accessoire_supprime');
            exit;
        }
        header('Location: /admin/accessoires?error=suppression_impossible');
        exit;
    }
}
