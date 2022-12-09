<?php

namespace App\Controller\Admin;


use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Category;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class ProductCrudController extends AbstractCrudController
{
 public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
 }


    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
		IdField::new('id')->setDisabled(),
		 AssociationField::new('categories')
                ->autocomplete(function (array $datas, Product $product) {
                    foreach ($datas as $data) {
                        $category = (new Category())
                            ->setTitle($data);
                        $this->entityManager->persist($category);
                        $product->addCategory($category);
                    }
                }),
            AssociationField::new('price')
                ->setSortable(false)
                ->renderAsEmbeddedForm(),
            TextField::new('title'),
            BooleanField::new('sale'),
        ];
    }
}
