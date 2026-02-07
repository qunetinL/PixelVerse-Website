<?php

namespace PixelVerseApp\Controllers;

use PixelVerseApp\Models\Character;
use PixelVerseApp\Models\Accessory;
use PixelVerseApp\Core\Database;

class CharacterController extends BaseController
{
    private $characterModel;

    public function __construct()
    {
        // Protection de la route : l'utilisateur doit être connecté
        if (!isset($_SESSION['user'])) {
            header('Location: /connexion');
            exit;
        }
        $this->characterModel = new Character();
    }

    /**
     * Liste des personnages de l'utilisateur
     */
    public function index()
    {
        $userId = $_SESSION['user']['id'];
        $characters = $this->characterModel->getByUserId($userId);

        $this->render('characters/index', [
            'title' => 'Mes Personnages - PixelVerse',
            'characters' => $characters
        ]);
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        $accessoryModel = new Accessory();
        $accessories = [
            'armes' => $accessoryModel->getActiveByType('arme'),
            'vetements' => $accessoryModel->getActiveByType('vetement'),
            'accessoires' => $accessoryModel->getActiveByType('accessoire')
        ];

        $this->render('characters/create', [
            'title' => 'Création de Personnage - PixelVerse',
            'accessories' => $accessories
        ]);
    }

    /**
     * Enregistre un nouveau personnage
     */
    public function store()
    {
        $userId = $_SESSION['user']['id'];

        $data = [
            'user_id' => $userId,
            'name' => $_POST['name'] ?? '',
            'gender' => $_POST['gender'] ?? 'homme',
            'skin_color' => $_POST['skin_color'] ?? '#ffffff',
            'hair_style' => $_POST['hair_style'] ?? 'court',
            'status' => 'pending'
        ];

        // Validation du nom unique
        if ($this->characterModel->nameExists($data['name'])) {
            return $this->render('characters/create', [
                'title' => 'Création de Personnage - PixelVerse',
                'error' => 'Ce nom de personnage est déjà utilisé.',
                'data' => $data
            ]);
        }

        if ($this->characterModel->create($data)) {
            $db = Database::getInstance()->getConnection();
            $charId = $db->lastInsertId();

            // Équipement des accessoires sélectionnés
            if (isset($_POST['accessories']) && is_array($_POST['accessories'])) {
                $this->characterModel->syncAccessories($charId, $_POST['accessories']);
            }

            header('Location: /mes-personnages?success=personnage_cree');
            exit;
        }

        $this->render('characters/create', [
            'title' => 'Création de Personnage - PixelVerse',
            'error' => 'Une erreur est survenue lors de la création.',
            'data' => $data
        ]);
    }

    /**
     * Supprime un personnage (soft delete)
     */
    public function delete()
    {
        $id = $_GET['id'] ?? null;
        $userId = $_SESSION['user']['id'];

        if ($id && $this->characterModel->softDelete((int) $id, $userId)) {
            header('Location: /mes-personnages?success=personnage_supprime');
            exit;
        }

        header('Location: /mes-personnages?error=suppression_impossible');
        exit;
    }
}
