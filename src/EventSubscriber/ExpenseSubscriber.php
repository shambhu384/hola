<?php

namespace App\EventSubscriber;

use App\Utils\Ntfy;
use App\Entity\Expense;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;

class ExpenseSubscriber implements EventSubscriberInterface
{
    public function __construct(private Ntfy $ntfy) {}

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

        if (!($entity instanceof Expense)) {
            return;
        }

        $this->ntfy->send(
            sprintf('₹ %s added for %s', $entity->getAmount(), $entity->getCategory()->getName()),
            [
                'Title' => 'New Expense Added',
                'Priority' => 'urgent',
                'Tags' => 'warning,skull'
            ]
        );
    }

    public function sendNotificationOnUpdate(AfterEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Expense)) {
            return;
        }

        $this->ntfy->send(
            sprintf('₹%d updated for %s', $entity->getAmount(), $entity->getCategory()->getName()),
            [
                'Title' => 'New Expense Added',
                'Priority' => 'urgent',
                'Tags' => 'warning,skull'
            ]
        );
    }
}