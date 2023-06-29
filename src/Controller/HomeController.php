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

        $client = new Client();
        $response = $client->request('GET', $url, [
            'query' => [
                'key' => $apiKey,
                //'genres' => 'indie',
            ],'curl' => [
                CURLOPT_SSL_VERIFYPEER => false,
            ],
        ]);
        
        $statusCode = $response->getStatusCode();
        $content = $response->getBody()->getContents();
        $games = json_decode($content, true);
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'status_code' => $statusCode,
            'games' => $games['results'],
        ]);
    }
}
