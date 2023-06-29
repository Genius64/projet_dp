<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Client;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        $apiKey = 'ab38bfe090bf476b84e7454698452d66';
        $url = 'https://api.rawg.io/api/games';
    $genresUrl = 'https://api.rawg.io/api/genres';

    $client = new Client();

    // Récupérer les jeux les mieux notés
    $topRatedResponse = $client->request('GET', $url, [
        'query' => [
            'key' => $apiKey,
            'ordering' => '-rating', // Pour obtenir les jeux par ordre décroissant de note
        ], 'curl' =>[
            CURLOPT_SSL_VERIFYPEER => false,
        ],
    ]);
    $topRatedContent = $topRatedResponse->getBody()->getContents();
    $topRatedGames = json_decode($topRatedContent, true)['results'];

    // Récupérer la liste des genres
    $genresResponse = $client->request('GET', $genresUrl, [
        'query' => [
            'key' => $apiKey,
        ], 'curl' =>[
            CURLOPT_SSL_VERIFYPEER => false,
        ],
    ]);
    $genresContent = $genresResponse->getBody()->getContents();
    $genres = json_decode($genresContent, true)['results'];

    // Récupérer les jeux par genre
    $gamesByGenre = [];

    foreach ($genres as $genre) {
        $genreSlug = $genre['slug'];
        $genreResponse = $client->request('GET', $url, [
            'query' => [
                'key' => $apiKey,
                'genres' => $genreSlug,
            ], 'curl' =>[
                CURLOPT_SSL_VERIFYPEER => false,
            ],
        ]);
        $genreContent = $genreResponse->getBody()->getContents();
        $genreGames = json_decode($genreContent, true)['results'];
        $gamesByGenre[$genreSlug] = $genreGames;
    }

    return $this->render('home/index.html.twig', [
        'controller_name' => 'HomeController',
        'topRatedGames' => $topRatedGames,
        'gamesByGenre' => $gamesByGenre,
        'genres' => $genres,
    ]);
    }
}
