<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\Expense;
use App\Entity\Income;
use App\Entity\Notification;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class NotificationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Notification::class;
    }

    public function createEntity(string $entityFqcn)
    {
        $notification = new Notification();
        $notification->setPriority('default');
        return $notification;
    }



    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('Basic')->collapsible(),
            TextField::new('title'),
            DateTimeField::new('sendAt'),
            BooleanField::new('isRecurring'),
            TextareaField::new('message'),
            ChoiceField::new('priority')->setChoices([
                'urgent' => 'urgent',
                'high' => 'high',
                'default' => 'default',
                'low' => 'low'
            ]),
            FormField::addPanel('Advance')->renderCollapsed(),
            ChoiceField::new('resource')->setChoices([
                'Event' => Event::class,
                'Income' => Income::class,
                'Expense' => Expense::class,
                'User' => User::class
            ]),
            TextareaField::new('query'),
            AssociationField::new('tags'),
            
        ];
    }
}
