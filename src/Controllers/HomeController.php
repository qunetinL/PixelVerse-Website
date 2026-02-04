<?php

namespace PixelVerseApp\Controllers;

use PixelVerseApp\Core\Database;

/**
 * Contrôleur pour la page d'accueil
 */
class HomeController extends BaseController
{
    /**
     * Affiche la page d'accueil avec ses données
     */
    public function index()
    {
        // Test de connexion à la base de données
        $dbStatus = 'Inconnu';
        try {
            Database::getInstance()->getConnection();
            $dbStatus = 'OK';
        } catch (\Exception $e) {
            $dbStatus = 'Erreur : ' . $e->getMessage();
        }

        // Données des cartes de fonctionnalités (Hero section)
        $features = [
            [
                'icon' => 'fas fa-wand-magic-sparkles',
                'title' => 'Personnalisation Totale',
                'description' => 'Modifiez chaque aspect de votre avatar, des yeux à l\'armure.'
            ],
            [
                'icon' => 'fas fa-users',
                'title' => 'Communauté Active',
                'description' => 'Partagez vos créations et votez pour les meilleurs designs.'
            ],
            [
                'icon' => 'fas fa-shield-halved',
                'title' => 'Système RPG',
                'description' => 'Gérez vos statistiques et préparez-vous pour l\'aventure.'
            ]
        ];

        // Rendu de la vue 'home'
        $this->render('home', [
            'title' => 'FantasyRealm Online - PixelVerse',
            'dbStatus' => $dbStatus,
            'features' => $features
        ]);
    }
}
