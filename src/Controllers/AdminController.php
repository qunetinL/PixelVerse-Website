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
     * Affiche la liste des employés (Admin uniquement)
     */
    public function employees()
    {
        if ($_SESSION['user']['role'] !== 'admin') {
            header('Location: /admin/logs');
            exit;
        }

        $userModel = new \PixelVerseApp\Models\User();
        $employees = $userModel->getAllByRole('employe');

        $this->render('admin/employees/index', [
            'title' => 'Gestion des Employés - PixelVerse',
            'employees' => $employees
        ]);
    }

    /**
     * Gère la création d'un employé (Admin uniquement)
     */
    public function createEmployee()
    {
        if ($_SESSION['user']['role'] !== 'admin') {
            header('Location: /');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new \PixelVerseApp\Models\User();
            $data = [
                'pseudo' => $_POST['pseudo'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? '',
                'role' => 'employe'
            ];

            // Validation simple
            if (empty($data['pseudo']) || empty($data['email']) || empty($data['password'])) {
                $_SESSION['error'] = "Tous les champs sont obligatoires.";
            } elseif ($userModel->findByEmail($data['email'])) {
                $_SESSION['error'] = "Cet email est déjà utilisé.";
            } else {
                $userModel->create($data);
                $_SESSION['success'] = "Compte employé créé avec succès.";
            }
        }

        header('Location: /admin/employes');
        exit;
    }

    /**
     * Gère la suppression d'un employé (Admin uniquement)
     */
    public function deleteEmployee()
    {
        if ($_SESSION['user']['role'] !== 'admin') {
            header('Location: /');
            exit;
        }

        $id = $_POST['id'] ?? null;
        if ($id) {
            $userModel = new \PixelVerseApp\Models\User();
            $user = $userModel->findById((int) $id);

            if ($user && $user['role'] === 'employe') {
                $userModel->delete((int) $id);
                $_SESSION['success'] = "Employé supprimé avec succès.";
            }
        }

        header('Location: /admin/employes');
        exit;
    }

    /**
     * Affiche la liste des utilisateurs pour modération
     */
    public function users()
    {
        $userModel = new \PixelVerseApp\Models\User();
        $users = $userModel->getAllUsers();

        $this->render('admin/users/index', [
            'title' => 'Gestion des Utilisateurs - PixelVerse',
            'users' => $users
        ]);
    }

    /**
     * Bascule la suspension d'un utilisateur (Admin/Employé)
     */
    public function toggleSuspension()
    {
        $id = $_POST['id'] ?? null;
        if ($id) {
            $userModel = new \PixelVerseApp\Models\User();
            $userModel->toggleSuspension((int) $id);
            $_SESSION['success'] = "Statut de l'utilisateur mis à jour.";
        }

        header('Location: /admin/utilisateurs');
        exit;
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
