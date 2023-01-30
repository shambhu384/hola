<?php

namespace App\EventSubscriber;

use App\Controller\Admin\ExpenseCrudController;
use CalendarBundle\Entity\Event;
use CalendarBundle\CalendarEvents;
use App\Repository\ExpenseRepository;
use App\Repository\EventRepository;
use CalendarBundle\Event\CalendarEvent;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: CalendarEvents::SET_DATA, method: 'onCalendarSetData')]
class CalendarSubscriber
{
    public function __construct(
        private UrlGeneratorInterface $router,
        private ExpenseRepository $expenseRepository,
        private EventRepository $eventRepository,
        private AdminUrlGenerator $adminUrlGenerator
    ) {
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        $events = $this->eventRepository->findByDates($start, $end);
        foreach ($events as $event) {
            $bookingEvent = new Event(
                $event->getName(),
                $event->getEventDate()
            );
            $bookingEvent->setOptions([
                'backgroundColor' => 'rgba(211, 0, 109, 0.8)',
                'borderColor' => 'rgba(75, 192, 192, 0.2)',
            ]);
            $calendar->addEvent($bookingEvent);
        }

        $expenses = $this->expenseRepository->findByDates($start, $end);

        foreach ($expenses as $expense)
        {
            $bookingEvent = new Event(
                sprintf('%s %d', $expense->getCategory()->getName(), ($expense->getAmount() / 100)),
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
