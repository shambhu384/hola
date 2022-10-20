<?php

namespace App\EventSubscriber;

use App\Entity\Rfc;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    public function __construct(private HttpClientInterface $ntfyClient, private Security $security) {}

    public static function getSubscribedEvents()
    {
        return [
            AfterEntityPersistedEvent::class => ['sendNotificationOnCreate'],
            AfterEntityUpdatedEvent::class => ['sendNotificationOnUpdate']
        ];
    }

    public function sendNotificationOnCreate(AfterEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Rfc)) {
            return;
        }

        $this->send();
    }

    public function sendNotificationOnUpdate(AfterEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Rfc)) {
            return;
        }

        $this->send();
    }

    private function send()
    {
        $response = $this->ntfyClient->request('POST', sprintf('https://ntfy.sh/%s', $this->security->getUser()->getNtfyTopic()), [
            'body' => 'raw data',
            'headers' => [
                'Content-Type' => 'text/plain',
            ],
        ]);
    }
}