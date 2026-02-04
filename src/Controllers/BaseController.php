<?php

namespace PixelVerseApp\Controllers;

/**
 * Contrôleur de base contenant les méthodes de rendu communes
 */
abstract class BaseController
{
    /**
     * Rend une vue complète avec un layout optionnel
     * @param string $view Nom de la vue (sans .php)
     * @param array $data Variables à extraire dans la vue
     * @param string|null $layout Nom du layout wrapper
     */
    protected function render($view, $data = [], $layout = 'main')
    {
        // Rend les variables accessibles dans la vue (ex: $title)
        extract($data);

        $viewFile = dirname(__DIR__) . '/Views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            die("La vue $view est introuvable.");
        }

        if ($layout) {
            // Capture du contenu de la vue
            ob_start();
            require $viewFile;
            $content = ob_get_clean();

            // Chargement du layout qui affichera $content
            $layoutFile = dirname(__DIR__) . '/Views/layout/' . $layout . '.php';
            if (file_exists($layoutFile)) {
                require $layoutFile;
            } else {
                die("Le layout $layout est introuvable.");
            }
        } else {
            // Rendu direct sans layout
            require $viewFile;
        }
    }

    /**
     * Rend une portion de vue (partial)
     * @param string $view Nom du fichier dans Views/partials/
     * @param array $data Variables spécifiques au partial
     */
    protected function renderPartial($view, $data = [])
    {
        extract($data);
        $viewFile = dirname(__DIR__) . '/Views/partials/' . $view . '.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo "Le partial $view est introuvable.";
        }
    }
}
