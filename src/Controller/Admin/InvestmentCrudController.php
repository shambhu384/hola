<?php

namespace App\Controller\Admin;

use App\Entity\Investment;
use Symfony\UX\Chartjs\Model\Chart;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\PercentField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class InvestmentCrudController extends AbstractCrudController
{
    public function __construct(private ChartBuilderInterface $chartBuilder)
    {
        
    }

    public static function getEntityFqcn(): string
    {
        return Investment::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return Crud::new()
            ->setHelp('index', '...')
            ->overrideTemplate('crud/index', 'admin/crud/index.html.twig')
        ;
    }
    
    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(ChoiceFilter::new('payFrequency')->setChoices([
                'Onetime' => 'onetime',
                'Multiple' => 'multiple',
                'Biweekly' => 'biweekly',
                'Semimonthly' => 'semimonthly',
                'Monthly' => 'monthly',
                'Quarterly' => 'quarterly',
                'Semi-annually' => 'semiannually',
                'Annually' => 'annually'
            ]))
            ->add(ChoiceFilter::new('investmentOption')->setChoices([
                'Equity' => 'equity',
                'Mutal Fund' => 'mutalfund',
                'Deposit' => 'deposit',
                'Gold' => 'gold',
                'NPS' => 'nps',
                'ULIP' => 'ulip',
                'PPF' => 'ppf',
                'EPF' => 'epf',
                'Insurance' => 'insurance',
                'Crypto' => 'crypto',
            ]))
        ;
    }

    public function configureFields(string $pageName): iterable
    {

        if ($pageName == 'index') {
            return [
                TextField::new('name')
                    ->setTemplatePath('admin/field/linkedit.html.twig')
                ,
                ChoiceField::new('investmentOption')->setChoices([
                    'Equity' => 'equity',
                    'Mutal Fund' => 'mutalfund',
                    'Deposit' => 'deposit',
                    'Gold' => 'gold',
                    'NPS' => 'nps',
                    'ULIP' => 'ulip',
                    'PPF' => 'ppf',
                    'EPF' => 'epf',
                    'Insurance' => 'insurance',
                    'Crypto' => 'crypto',
                 ])->setLabel('Option'),
                ChoiceField::new('taxDeduction')->setChoices([
                    '80C' => '80C',
                    '80CCC' => '80CCC',
                    '80CCD' => '80CCD',
                    '80D' => '80D'
                ])->setLabel('Tax'),
                ChoiceField::new('payFrequency')->setChoices([
                    'Onetime' => 'onetime',
                    'Multiple' => 'multiple',
                    'Biweekly' => 'biweekly',
                    'Semimonthly' => 'semimonthly',
                    'Monthly' => 'monthly',
                    'Quarterly' => 'quarterly',
                    'Semi-annually' => 'semiannually',
                    'Annually' => 'annually'
                ])->setLabel('Frequency'),
                DateField::new('startOn'),
                DateField::new('nextDueDate'),
                MoneyField::new('amount')->setCurrency('INR'),
                MoneyField::new('amountInvested')->setCurrency('INR')->setLabel('Invested'),
                PercentField::new('interestGain')->setNumDecimals(2)->setStoredAsFractional(true)->setLabel('%'),
                MoneyField::new('currentValue')->setCurrency('INR')
            ];
        }

        return [
            FormField::addPanel('Basic'),
            TextField::new('name'),
            ChoiceField::new('payFrequency')->setChoices([
                'Onetime' => 'onetime',
                'Multiple' => 'multiple',
                'Biweekly' => 'biweekly',
                'Semimonthly' => 'semimonthly',
                'Monthly' => 'monthly',
                'Quarterly' => 'quarterly',
                'Semi-annually' => 'semiannually',
                'Annually' => 'annually'
            ]),
          
            DateField::new('startOn'),
            DateField::new('nextDueDate'),
            MoneyField::new('amount')->setCurrency('INR'),
            FormField::addPanel('Advance')->renderCollapsed(),
            ChoiceField::new('investmentOption')->setChoices([
                'Equity' => 'equity',
                'Mutal Fund' => 'mutalfund',
                'Deposit' => 'deposit',
                'Gold' => 'gold',
                'NPS' => 'nps',
                'ULIP' => 'ulip',
                'PPF' => 'ppf',
                'EPF' => 'epf',
                'Insurance' => 'insurance',
                'Crypto' => 'crypto',
            ]),
            ChoiceField::new('taxDeduction')->setChoices([
                '80C' => '80C',
                '80CCC' => '80CCC',
                '80CCD' => '80CCD',
                '80D' => '80D'
            ]),
            IntegerField::new('interestGain'),
            TextField::new('reference'),
        ];
    }


    public function configureResponseParameters(KeyValueStore $responseParameters): KeyValueStore
    {

        $chart = $this->chartBuilder->createChart(Chart::TYPE_BAR);

        $chart->setData([
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [0, 10, 5, 2, 20, 30, 45],
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 50,
                ],
            ],
        ]);


        $responseParameters->set('chart', $chart);
        return $responseParameters;
    }
}
