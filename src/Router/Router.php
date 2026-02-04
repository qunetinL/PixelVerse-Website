<?php

namespace PixelVerseApp\Router;

/**
 * Routeur simple pour dispatcher les requêtes vers les contrôleurs
 */
class Router
{
    private array $routes = [];

    /**
     * Ajoute une nouvelle route à l'application
     * @param string $method Méthode HTTP (GET, POST, etc.)
     * @param string $path Chemin de l'URL
     * @param mixed $handler Contrôleur et méthode ou fonction de rappel
     */
    public function add(string $method, string $path, $handler): void
    {
        $this->routes[] = compact('method', 'path', 'handler');
    }

    /**
     * Analyse la requête entrante et exécute le handler correspondant
     */
    public function dispatch(string $requestedMethod, string $requestedPath): void
    {
        // Nettoyage de l'URL pour ignorer les paramètres GET lors du matching
        $requestedPath = parse_url($requestedPath, PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestedMethod || $route['path'] !== $requestedPath) {
                continue;
            }

            $handler = $route['handler'];

            // Appel de la méthode du contrôleur : [Controller::class, 'methode']
            if (is_array($handler)) {
                [$controllerName, $methodName] = $handler;
                (new $controllerName())->$methodName();
                return;
            }

            // Exécution d'une fonction anonyme si fournie
            if (is_callable($handler)) {
                $handler();
                return;
            }
        }

        // Si aucune route ne correspond, renvoi d'une erreur 404
        http_response_code(404);
        echo "404 Not Found";
    }
}
