<?php

namespace App\Controller\Admin;

use DateTime;
use App\Entity\Expense;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ExpenseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Expense::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('category')
            ->add('dateOfPayment')
            ->add(ChoiceFilter::new('modeOfPayment')->setChoices([
                'Online' => 'online',
                'Cash' => 'cash',
                'Card Full Payment' => 'cardfullpay',
                'Card EMI' => 'cardemi'
            ]))
        ;
    }
    public function createEntity(string $entityFqcn)
    {
        $expense = new Expense();
        $expense->setDateOfPayment(new \DateTimeImmutable('today'));
        $expense->setCreatedAt(new \DateTimeImmutable('now'));
        $expense->setModeOfPayment('online');


        return $expense;
    }

    public function configureFields(string $pageName): iterable
    {
        if ($pageName == 'index') {
            return [
                // AssociationField::new('category')->formatValue(function($value, $category) {
                //     return sprintf('<i class="fa %s"></i>  %s', $category->getCategory()->getFaIcon(), $category->getCategory()->getName());
                // }),
                AssociationField::new('category'),
                DateField::new('dateOfPayment'),
                ChoiceField::new('modeOfPayment')->setChoices([
                    'Online' => 'online',
                    'Cash' => 'cash',
                    'Card Full Payment' => 'cardfullpay',
                    'Card EMI' => 'cardemi'
                ]),
                MoneyField::new('amount')->setCurrency('INR'),
                TextareaField::new('hint')
                ->setCustomOption(TextareaField::OPTION_NUM_OF_ROWS, 2)
            ];
        }
        return [
            AssociationField::new('category'),
            DateField::new('dateOfPayment'),
            ChoiceField::new('modeOfPayment')->setChoices([
                'Online' => 'online',
                'Cash' => 'cash',
                'Card Full Payment' => 'cardfullpay',
                'Card EMI' => 'cardemi'
            ]),
            MoneyField::new('amount')->setCurrency('INR'),
            TextareaField::new('hint')
            ->setCustomOption(TextareaField::OPTION_NUM_OF_ROWS, 2)
        ];
    }
}
