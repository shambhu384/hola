<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:site-admin',
    description: 'Add a short description for your command',
)]
class SiteAdminCommand extends Command
{
    public function __construct(
	    private EntityManagerInterface $entityManager,
	    private UserPasswordHasherInterface $passwordHasher,
        private UserRepository $userRepository
    ) {
    	parent::__construct();
    }

    
    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if (!$this->userRepository->findOneBy(['email' => 'admin@example.com'])) {
            $user = new User();
            $user->setEmail('admin@example.com');
            $plaintextPassword = 'Demo@100';
    
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_ADMIN']);
            $this->entityManager->persist($user);
        }     

        $this->entityManager->flush();

        $io->success('Site admin created');

        return Command::SUCCESS;
    }
}
