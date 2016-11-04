<?php

namespace Subdee\FcmBundle\Services;


use GuzzleHttp\Client as HttpClient;
use paragraph1\phpFCM\Client;
use paragraph1\phpFCM\Message;
use paragraph1\phpFCM\Notification;
use paragraph1\phpFCM\Recipient\Device;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PushNotificationService
{
    private $apiKey;

    public function __construct(ContainerInterface $container)
    {
        $this->apiKey = $container->getParameter('fcm.push.api_key');
    }

    public function sendNotification(string $title, string $body, array $devices, int $count = 1)
    {
        $client = new Client();
        $client->setApiKey($this->apiKey);
        $client->injectHttpClient(new HttpClient());

        $note = new Notification($title, $body);
        $note->setBadge($count);

        foreach ($devices as $device) {
            $message = new Message();
            $message->addRecipient(new Device($device));
            $message->setNotification($note);

            $client->send($message);
        }
    }
}