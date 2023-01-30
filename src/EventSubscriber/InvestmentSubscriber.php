<?php

namespace App\EventSubscriber;

use App\Entity\Investment;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: BeforeCrudActionEvent::class, method: 'setBlogPostSlug')]
class InvestmentSubscriber
{
    public function setBlogPostSlug(BeforeCrudActionEvent $event)
    {
        //dump($event);
    }
}