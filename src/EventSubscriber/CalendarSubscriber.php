<?php

namespace App\EventSubscriber;

use App\Controller\Admin\ExpenseCrudController;
use CalendarBundle\Entity\Event;
use CalendarBundle\CalendarEvents;
use App\Repository\ExpenseRepository;
use CalendarBundle\Event\CalendarEvent;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    public function __construct(private UrlGeneratorInterface $router, private ExpenseRepository $expenseRepository, private AdminUrlGenerator $adminUrlGenerator){}

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        $expenses = $this->expenseRepository->findByDates($start, $end);

        foreach($expenses as $expense) {

            $bookingEvent = new Event(
                sprintf('%s %d', $expense->getCategory()->getFaIcon(), $expense->getAmount()),
                $expense->getDateOfPayment()
            );
    
            $bookingEvent->setOptions([
                'backgroundColor' => 'rgb(75, 192, 192)',
                'borderColor' => 'rgba(75, 192, 192, 0.2)',
            ]);

            $url = $this->adminUrlGenerator
                        ->setController(ExpenseCrudController::class)
                        ->setAction('detail')
                        ->setEntityId($expense->getId())
                        ->generateUrl()
            ;

            $bookingEvent->addOption('url', $url);
    
            $calendar->addEvent($bookingEvent);
        }
    }
}