<?php

namespace PixelVerseApp\Controllers;

use PixelVerseApp\Models\User;

class AuthController extends BaseController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Affiche le formulaire de connexion
     */
    public function showLogin()
    {
        if (isset($_SESSION['user'])) {
            header('Location: /');
            exit;
        }
        $this->render('auth/login', ['title' => 'Connexion - PixelVerse']);
    }

    /**
     * Gère la soumission du formulaire de connexion
     */
    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->userModel->login($email, $password);

        if ($user && !isset($user['error'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'pseudo' => $user['pseudo'],
                'role' => $user['role']
            ];
            header('Location: /');
            exit;
        }

        $error = $user['error'] ?? 'Identifiants invalides.';
        $this->render('auth/login', [
            'title' => 'Connexion - PixelVerse',
            'error' => $error,
            'email' => $email
        ]);
    }

    /**
     * Affiche le formulaire d'inscription
     */
    public function showRegister()
    {
        if (isset($_SESSION['user'])) {
            header('Location: /');
            exit;
        }
        $this->render('auth/register', ['title' => 'Inscription - PixelVerse']);
    }

    /**
     * Gère la soumission du formulaire d'inscription
     */
    public function register()
    {
        $data = [
            'pseudo' => $_POST['pseudo'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'role' => 'joueur' // Rôle par défaut
        ];

        // Validation basique (à compléter pour une prod réelle)
        if (empty($data['pseudo']) || empty($data['email']) || empty($data['password'])) {
            return $this->render('auth/register', [
                'title' => 'Inscription - PixelVerse',
                'error' => 'Tous les champs sont obligatoires.',
                'data' => $data
            ]);
        }

        // Vérification de l'existence de l'utilisateur (Email)
        if ($this->userModel->findByEmail($data['email'])) {
            return $this->render('auth/register', [
                'title' => 'Inscription - PixelVerse',
                'error' => 'Cet email est déjà utilisé.',
                'data' => $data
            ]);
        }

        // Vérification de l'existence de l'utilisateur (Pseudo)
        if ($this->userModel->findByPseudo($data['pseudo'])) {
            return $this->render('auth/register', [
                'title' => 'Inscription - PixelVerse',
                'error' => 'Ce pseudo est déjà utilisé.',
                'data' => $data
            ]);
        }

        if ($this->userModel->create($data)) {
            // Auto-connexion après inscription
            $user = $this->userModel->findByEmail($data['email']);
            $_SESSION['user'] = [
                'id' => $user['id'],
                'pseudo' => $user['pseudo'],
                'role' => $user['role']
            ];

            header('Location: /?success=compte_cree');
            exit;
        }

        $this->render('auth/register', [
            'title' => 'Inscription - PixelVerse',
            'error' => 'Une erreur est survenue lors de la création du compte.',
            'data' => $data
        ]);
    }

    /**
     * Déconnexion de l'utilisateur
     */
    public function logout()
    {
        unset($_SESSION['user']);
        session_destroy();
        header('Location: /');
        exit;
    }

    /**
     * Affiche le formulaire de mot de passe oublié
     */
    public function showForgotPassword()
    {
        $this->render('auth/forgot-password', ['title' => 'Mot de passe oublié - PixelVerse']);
    }

    /**
     * Gère la demande de réinitialisation
     */
    public function forgotPassword()
    {
        $email = $_POST['email'] ?? '';
        $token = $this->userModel->generateResetToken($email);

        if ($token) {
            // Ici, on simulerait l'envoi d'un mail
            // Pour le projet, on affiche juste un message de succès
            return $this->render('auth/forgot-password', [
                'title' => 'Mot de passe oublié - PixelVerse',
                'success' => "Un lien de réinitialisation a été généré (Simulation). Token: $token"
            ]);
        }

        $this->render('auth/forgot-password', [
            'title' => 'Mot de passe oublié - PixelVerse',
            'error' => "Aucun compte n'est associé à cet email."
        ]);
    }
}
