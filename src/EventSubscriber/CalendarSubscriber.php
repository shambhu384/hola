<?php

namespace App\EventSubscriber;

use CalendarBundle\Entity\Event;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    public function __construct(private UrlGeneratorInterface $router){}

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

        $bookingEvent = new Event(
            'Event 1',
            new \DateTime('Tuesday this week'),
            new \DateTime('Wednesdays this week')
        );

        /*
         * Add custom options to events
         *
         * For more information see: https://fullcalendar.io/docs/event-object
         * and: https://github.com/fullcalendar/fullcalendar/blob/master/src/core/options.ts
         */

        $bookingEvent->setOptions([
            'backgroundColor' => 'rgb(75, 192, 192)',
            'borderColor' => 'rgba(75, 192, 192, 0.2)',
        ]);
        $bookingEvent->addOption(
            'url',
            $this->router->generate('admin_dashboard')
        );

        // finally, add the event to the CalendarEvent to fill the calendar
        $calendar->addEvent($bookingEvent);


        // If the end date is null or not defined, it creates a all day event
        $event = new Event(
            'All day event',
            new \DateTime('Friday this week')
        );
        $event->setOptions([
            'backgroundColor' => 'rgb(153, 102, 255)',
            'borderColor' => 'rgba(153, 102, 255, 0.2)',
        ]);
        $event->addOption(
            'url',
            $this->router->generate('admin_dashboard')
        );

        $calendar->addEvent($event);
    }
}