<?php

namespace App\Controller\Admin;

use App\Entity\Price;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PriceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Price::class;
    }

    public function configureFields(string $pageName): iterable
    {
    
        if (Crud::PAGE_NEW === $pageName) {
            return [
                IntegerField::new('amount')
                    ->setColumns('col-md-6 col-xxl-5')
                    ->setLabel('Price [INFO: YOU ARE ADDING A NEW PRICE]'),
                TextField::new('currency'),
            ];
        }

        return [
            IntegerField::new('amount')
                ->setColumns('col-md-6 col-xxl-5')
                ->setLabel('Price [INFO: YOU ARE EDITING AN EXISTING PRICE]'),
            TextField::new('currency'),
        ];
    }
}
