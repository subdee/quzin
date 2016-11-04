<?php

namespace Subdee\FcmBundle\Services;


use GuzzleHttp\Client as HttpClient;
use paragraph1\phpFCM\Client;
use paragraph1\phpFCM\Message;
use paragraph1\phpFCM\Notification;
use paragraph1\phpFCM\Recipient\Device;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CustomSearchService
{
    private $apiKey;
    private $searchId;

    public function __construct(ContainerInterface $container)
    {
        $this->apiKey = $container->getParameter('fcm.search.api_key');
        $this->searchId = $container->getParameter('fcm.search.search_id');
    }

    public function search(string $query)
    {
        $client = new HttpClient();

        $response = $client->get('https://www.googleapis.com/customsearch/v1?key=' . $this->apiKey . '&cx=' . $this->searchId . '&q=' . $query);
        $body = json_decode($response->getBody());
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }
        return $body->items ?? [];
    }
}