<?php

namespace PixelVerseApp\Controllers;

use PixelVerseApp\Models\Character;

class PageController extends BaseController
{
    private $characterModel;

    public function __construct()
    {
        $this->characterModel = new Character();
    }

    public function galerie()
    {
        $filters = [
            'gender' => $_GET['gender'] ?? '',
            'pseudo' => $_GET['pseudo'] ?? '',
            'sort' => $_GET['sort'] ?? 'desc'
        ];

        $characters = $this->characterModel->getFilteredApproved($filters);

        $this->render('pages/galerie', [
            'title' => 'Galerie des Personnages - PixelVerse',
            'characters' => $characters,
            'filters' => $filters
        ]);
    }

    public function contact()
    {
        $this->render('pages/contact', [
            'title' => 'Contactez-nous - PixelVerse'
        ]);
    }

    public function submitContact()
    {
        // Simulation d'un traitement de formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'] ?? '';
            $email = $_POST['email'] ?? '';
            $message = $_POST['message'] ?? '';

            // Ici on pourrait envoyer un mail ou enregistrer en BDD

            header('Location: /contact?success=message_envoye');
            exit;
        }

        header('Location: /contact');
        exit;
    }

    public function mentionsLegales()
    {
        $this->render('pages/mentions-legales', [
            'title' => 'Mentions Légales - PixelVerse'
        ]);
    }

    public function cgv()
    {
        $this->render('pages/cgv', [
            'title' => 'Conditions Générales de Vente - PixelVerse'
        ]);
    }

    public function enSavoirPlus()
    {
        $this->render('pages/en-savoir-plus', [
            'title' => 'En Savoir Plus - PixelVerse'
        ]);
    }
}
