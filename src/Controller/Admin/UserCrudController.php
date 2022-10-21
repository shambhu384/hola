<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Controller\Admin\DashboardController;


class UserCrudController extends AbstractCrudController
{

   public function __construct(private AdminUrlGenerator $adminUrlGenerator) {}

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('email'),
            TextField::new('ntfyTopic')
                ->setHelp('Subscribe to a topic, know more https://ntfy.sh/')
        ];

    }

    protected function getRedirectResponseAfterSave(AdminContext $context, string $action): RedirectResponse
{
    $submitButtonName = $context->getRequest()->request->all()['ea']['newForm']['btn'];

    if ('saveAndReturn' === $submitButtonName) {
        $url = $this->adminUrlGenerator->setRoute('admin_dashboard')->generateUrl();

        return $this->redirect($url);
    }

    return parent::getRedirectResponseAfterSave($context, $action);
    }

}
