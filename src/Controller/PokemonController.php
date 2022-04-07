<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PokemonService;

class PokemonController extends AbstractController
{
    private $pokemonservice;

    public function __construct(PokemonService $poke)
    {
        $this->pokemonservice = $poke;
    }
    /**
     * @Route("/pokemon", name="app_pokemon")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PokemonController.php',
        ]);
    }

    /**
     * @Route("/pokemon/{name}", name="app_pokemon")
     */
    public function get_pokemon(string $name): Response
    {
        [$pokemon_info, $status] = $this->pokemonservice->getPokemonInfo($name);
        return $this->json($pokemon_info, $status);
    }
}
