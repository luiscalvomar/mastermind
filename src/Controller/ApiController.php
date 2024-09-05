<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;

class ApiController extends AbstractController
{
    #[Route('/', name: 'api_root')]
    /**
     * Root page with routes.
     */
    public function index(RouterInterface $router): JsonResponse
    {
        $routes = [];
        foreach ($router->getRouteCollection() as $routeName => $route) {
            $routes[$routeName] = $route->getPath();
        }

        $data = [
            'message' => 'Welcome to the Mastermind API',
            'routes' => $routes,
        ];

        return new JsonResponse($data);
    }
}
