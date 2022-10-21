<?php

namespace App\Controller\Admin;

use App\Entity\ExpenseCategory;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class ExpenseCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ExpenseCategory::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
		TextField::new('name'),
		TextField::new('faIcon'),
		IntegerField::new('budgetEstimate'),
		ChoiceField::new('occurrence')->setChoices([
			'One Time' => 'onetime',
			'Monthly' => 'monthly',
			'Annually' => 'annually'
		])
        ];
    }
}
