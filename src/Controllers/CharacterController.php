<?php

namespace PixelVerseApp\Controllers;

use PixelVerseApp\Models\Character;
use PixelVerseApp\Models\Accessory;
use PixelVerseApp\Models\Review;
use PixelVerseApp\Models\LogManager;
use PixelVerseApp\Core\Database;

class CharacterController extends BaseController
{
    private $characterModel;
    private $logManager;

    public function __construct()
    {
        // Protection de la route : l'utilisateur doit être connecté
        if (!isset($_SESSION['user'])) {
            header('Location: /connexion');
            exit;
        }
        $this->characterModel = new Character();
        $this->logManager = new LogManager();
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

            // Log de l'action
            $this->logManager->log((string) $userId, 'creation_personnage', [
                'name' => $data['name'],
                'id' => $charId
            ]);

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

    /**
     * Affiche les détails d'un personnage (Vue Publique)
     */
    public function show()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /');
            exit;
        }

        $character = $this->characterModel->getById((int) $id);
        if (!$character) {
            die("Personnage introuvable.");
        }

        // Récupérer les équipements
        $equipped = $this->characterModel->getAccessories((int) $id);

        // Récupérer les avis validés
        $reviewModel = new Review();
        $reviews = $reviewModel->getVisibleByCharacter((int) $id);

        // Vérifier si l'utilisateur a déjà voté
        $hasVoted = false;
        if (isset($_SESSION['user'])) {
            $hasVoted = $reviewModel->hasUserVoted($_SESSION['user']['id'], (int) $id);
        }

        $this->render('characters/show', [
            'title' => $character['name'] . ' - PixelVerse',
            'character' => $character,
            'equipped' => $equipped,
            'reviews' => $reviews,
            'hasVoted' => $hasVoted
        ]);
    }

    /**
     * Liste des personnages en attente de modération (Admin/Employé)
     */
    public function adminIndex()
    {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'employe'])) {
            header('Location: /mes-personnages?error=acces_refuse');
            exit;
        }

        $pending = $this->characterModel->getPending();

        $this->render('admin/characters', [
            'title' => 'Modération des Personnages - PixelVerse',
            'characters' => $pending
        ]);
    }

    /**
     * Approuve un personnage
     */
    public function approve()
    {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'employe'])) {
            header('Location: /');
            exit;
        }

        $id = $_GET['id'] ?? null;
        if ($id && $this->characterModel->updateStatus((int) $id, 'approved')) {
            // Log de l'action
            $this->logManager->log((string) $_SESSION['user']['id'], 'approbation_personnage', [
                'id' => $id
            ]);
            header('Location: /admin/personnages?success=personnage_approuve');
            exit;
        }

        header('Location: /admin/personnages?error=erreur');
        exit;
    }

    /**
     * Refuse un personnage
     */
    public function reject()
    {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'employe'])) {
            header('Location: /');
            exit;
        }

        $id = $_POST['id'] ?? null;
        $reason = $_POST['reason'] ?? '';

        if ($id && $this->characterModel->updateStatus((int) $id, 'rejected', $reason)) {
            // Log de l'action
            $this->logManager->log((string) $_SESSION['user']['id'], 'rejet_personnage', [
                'id' => $id,
                'reason' => $reason
            ]);
            header('Location: /admin/personnages?success=personnage_refuse');
            exit;
        }

        header('Location: /admin/personnages?error=erreur');
        exit;
    }

    /**
     * Duplique un personnage
     */
    public function duplicate()
    {
        $id = $_GET['id'] ?? null;
        $userId = $_SESSION['user']['id'];

        if (!$id) {
            header('Location: /mes-personnages');
            exit;
        }

        $char = $this->characterModel->getById((int) $id);
        if (!$char || $char['user_id'] !== $userId) {
            header('Location: /mes-personnages?error=acces_refuse');
            exit;
        }

        $newName = $char['name'] . ' (Copie)';

        // S'il existe déjà une copie avec ce nom, on ajoute un timestamp ou un random
        if ($this->characterModel->nameExists($newName)) {
            $newName = $char['name'] . ' (Copie ' . date('His') . ')';
        }

        if ($this->characterModel->duplicate((int) $id, $userId, $newName)) {
            // Log de l'action
            $this->logManager->log((string) $userId, 'duplication_personnage', [
                'source_id' => $id,
                'new_name' => $newName
            ]);
            header('Location: /mes-personnages?success=personnage_duplique');
            exit;
        }

        header('Location: /mes-personnages?error=erreur_duplication');
        exit;
    }
}
