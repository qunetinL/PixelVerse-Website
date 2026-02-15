<?php

namespace PixelVerseApp\Controllers;

use PixelVerseApp\Models\Review;
use PixelVerseApp\Models\LogManager;

class ReviewController extends BaseController
{
    private $reviewModel;
    private $logManager;

    public function __construct()
    {
        $this->reviewModel = new Review();
        $this->logManager = new LogManager();
    }

    /**
     * Enregistre un avis (Joueur)
     */
    public function store()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /connexion');
            exit;
        }

        $charId = $_POST['character_id'] ?? null;
        $rating = $_POST['rating'] ?? 5;
        $comment = $_POST['comment'] ?? '';

        if (!$charId || empty($comment)) {
            header('Location: /?error=donnees_invalides');
            exit;
        }

        // Vérifier si déjà voté
        if ($this->reviewModel->hasUserVoted($_SESSION['user']['id'], (int) $charId)) {
            header('Location: /personnage?id=' . $charId . '&error=deja_vote');
            exit;
        }

        $data = [
            'character_id' => (int) $charId,
            'user_id' => $_SESSION['user']['id'],
            'rating' => (int) $rating,
            'comment' => $comment
        ];

        if ($this->reviewModel->create($data)) {
            header('Location: /personnage?id=' . $charId . '&success=avis_en_attente');
            exit;
        }

        header('Location: /personnage?id=' . $charId . '&error=erreur_creation');
        exit;
    }

    /**
     * Liste des avis en attente (Admin/Employé)
     */
    public function adminIndex()
    {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'employe'])) {
            header('Location: /connexion');
            exit;
        }

        $reviews = $this->reviewModel->getPending();
        $this->render('admin/reviews', [
            'title' => 'Modération des Avis - PixelVerse',
            'reviews' => $reviews
        ]);
    }

    /**
     * Approuve un avis
     */
    public function approve()
    {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'employe'])) {
            exit('Accès refusé');
        }

        $id = $_GET['id'] ?? null;
        if ($id && $this->reviewModel->approve((int) $id)) {
            // Log de l'action
            $this->logManager->log((string) $_SESSION['user']['id'], 'approbation_avis', [
                'id' => $id
            ]);
            header('Location: /admin/avis?success=avis_approuve&mail_sent=1');
            exit;
        }
        header('Location: /admin/avis?error=action_impossible');
        exit;
    }

    /**
     * Supprime/Rejette un avis
     */
    public function delete()
    {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'employe'])) {
            exit('Accès refusé');
        }

        $id = $_GET['id'] ?? null;
        if ($id && $this->reviewModel->delete((int) $id)) {
            // Log de l'action
            $this->logManager->log((string) $_SESSION['user']['id'], 'suppression_avis', [
                'id' => $id
            ]);
            header('Location: /admin/avis?success=avis_supprime');
            exit;
        }
        header('Location: /admin/avis?error=action_impossible');
        exit;
    }
}
