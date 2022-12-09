<?php

namespace App\Controller\Admin;

use App\Entity;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\UX\Chartjs\Model\Chart;
use App\Repository\ExpenseRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Asset;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use Symfony\Component\HttpFoundation\RequestStack;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
		private ChartBuilderInterface $chartBuilder,
		private readonly UserRepository $userRepository,
        private readonly ExpenseRepository $expenseRepository,
		private readonly RequestStack $requestStack,
		private readonly Security $security,
	) {}
    
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(): Response
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => ['Jan', 'Feb', 'March', 'April', 'May', 'June'],
            'datasets' => [
                [
                    'label' => 'Investment 🍪',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
					'borderColor' => 'green',
					'borderWidth' => 1,
                    'data' => [10500, 20500, 30500, 40500, 50500, 60500]
                ],
                [
                    'label' => 'Expense 🏃‍♀️',
                    'borderWidth' => 1,
					'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
					'borderColor' => 'red',
                    'data' => [45000, 32800, 43000, 34000, 39000, 42000]
                ],
                [
                    'label' => 'Exempted Expense 🏃‍♀️',
                    'borderWidth' => 1,
					'backgroundColor' => 'black',
					'borderColor' => 'rgb(75, 192, 192)',
                    'data' => [40000, 32000, 41000, 39000, 38000,38000, 52000, 34000, 36520, 34520, 45000, 37000, 46000, 44000]
                ],
                [
                    'label' => 'Income 🍪',
                    'backgroundColor' => 'purple',
					'borderColor' => 'rgb(75, 192, 192)',
					'borderWidth' => 1,
                    'data' => [97850, 97850, 97850, 97850, 97850, 97850, 97850, 97850, 110000]
                ],
            ]
        ]);

        return $this->render('admin/my-dashboard.html.twig', ['chart' => $chart]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Pendu');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-solid fa-chart-line');
        yield MenuItem::section('Expense Managment');
        yield MenuItem::linkToCrud('Add Expense', 'fa fa-solid fa-hand-holding-dollar', Entity\Expense::class)->setAction('new');

        yield MenuItem::linkToCrud('Expenses', 'fa fa-sharp fa-cart-shopping', Entity\Expense::class)->setDefaultSort(['dateOfPayment' => 'DESC']);
        yield MenuItem::linkToCrud('Expense Categories', 'fa fa-tags', Entity\ExpenseCategory::class);
        
        yield MenuItem::section('Investment');

        yield MenuItem::linkToCrud('Events', 'fa fa-tags', Entity\Event::class);

        yield MenuItem::section('Development');
        yield MenuItem::linkToCrud('Request for Comments', 'fas fa-list', Entity\Rfc::class);
        yield MenuItem::linkToCrud('Request for Comments', 'fas fa-list', Entity\Product::class);
    }

    public function configureAssets(): Assets
	{
		$assets =  Assets::new();

		return $assets
			        ->addWebpackEncoreEntry('app')
                    ->addCssFile('build/admin.css')
                    ->addCssFile('https://cdn.jsdelivr.net/npm/@fullcalendar/core@4.1.0/main.min.css')
                    ->addCssFile('https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@4.1.0/main.min.css')
                    ->addCssFile('https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@4.1.0/main.min.css')
                    ->addJsFile('build/admin.js')
                    ->addJsFile('https://cdn.jsdelivr.net/npm/@fullcalendar/core@4.1.0/main.min.js')
                    ->addJsFile('https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@4.1.0/main.min.js')
                    ->addJsFile('https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@4.1.0/main.min.js')
                    ->addJsFile('https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@4.1.0/main.min.js')
                    ->addJsFile('https://code.jquery.com/jquery-3.6.0.min.js')
                    ->addHtmlContentToBody("<script>document.addEventListener('DOMContentLoaded', () => {
                        var calendarEl = document.getElementById('calendar-holder');
                        var calendar = new FullCalendar.Calendar(calendarEl, {
                        customButtons: {
                            myCustomButton: {
                            text: 'Add Event',
                                click: function() {
                                    $('#addEventModel').modal('show')
                                }
                            }
                        },
                        defaultView: 'dayGridMonth',
                        editable: true,
                        eventSources: [
                            {
                                url: '/fc-load-events',
                                    method: 'POST',
                                    extraParams: {
                                    filters: JSON.stringify({})
                            },
                            failure: () => {
                                // alert('There was an error while fetching FullCalendar!');
                                },
                            },
                        ],
                        header: {
                        left: 'prev,next today,myCustomButton',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,timeGridDay',
                        },
	                    plugins: [ 'interaction', 'dayGrid', 'timeGrid' ],
	                    timeZone: 'UTC',
	                });
                    calendar.render();
                    });</script>")
	                ->addHtmlContentToBody('<!-- generated at '.time().' -->')
        ;
	}

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        $menuLink =  MenuItem::linkToCrud('My Profile', 'fa fa-id-card', Entity\User::class)
                        ->setAction('edit')
                        ->setEntityId($this->security->getUser()->getId())
        ;

        return parent::configureUserMenu($user)
            // use this method if you don't want to display the user image
            ->displayUserAvatar(false)
            // you can use any type of menu item, except submenus
            ->addMenuItems([
                $menuLink,
            ]);
    }
}
