<?php

namespace App\Controller\Admin;

use App\Entity;
use App\Entity\User;
use App\Repository\UserRepository;
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
		private readonly RequestStack $requestStack,
		private readonly Security $security,
	) {}
    
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(): Response
    {
        return $this->render('admin/my-dashboard.html.twig', []);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Pendu');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('User', 'fas fa-list', Entity\User::class);
        yield MenuItem::linkToCrud('Request for Comments', 'fas fa-list', Entity\Rfc::class);

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
