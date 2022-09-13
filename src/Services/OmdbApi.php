<?php

namespace App\Services;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbApi
{
    public static $defaultUrlPoster = 'https://amc-theatres-res.cloudinary.com/amc-cdn/static/images/fallbacks/DefaultOneSheetPoster.jpg';
    private $client;
    private $apiKey = 'tagada';

    public function __construct(HttpClientInterface $client, ContainerBagInterface $params)
    {
        $this->client = $client;
        $this->apiKey = $params->get('app.omdbapi.key');
    }

    public function fetchOmdbData(string $titre): array
    {
       $response = $this->client->request(
            'GET',
            'https://www.omdbapi.com/?t=' . $titre . '&apikey=' . $this->apiKey
        );

        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray();
        

        return $content;
    }

    public function fetchPoster($titre)
    {
        $content = $this->fetchOmdbData($titre);
        
        return $content['Poster'] ?? OmdbApi::$defaultUrlPoster;
    }
}