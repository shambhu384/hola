<?php

namespace App\Utils;

use Exception;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class Ntfy
{
    public function __construct(private HttpClientInterface $httpClient, private Security $security) {}

    public function send(string $message, array $headers = [])
    {
        try
        {
            $this->httpClient->request(
                'POST',
                sprintf('https://ntfy.sh/%s', $this->security->getUser()->getNtfyTopic()),
                array(
                    'body' => $message,
                    'headers' => $headers
                )
            );
        } catch (Exception) {}
    }
}