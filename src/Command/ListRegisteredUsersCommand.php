<?php

namespace App\Command;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListRegisteredUsersCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this->setName('app:list-registered-users')
            ->setDescription('Outputs a list of registered users grouped by event');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $events = $this->entityManager->getRepository(Event::class)->findAll();

        if (empty($events)) {
            $output->writeln('No events found.');
            return Command::SUCCESS;
        }

        foreach ($events as $event) {
            $output->writeln('<info>' . $event->getEventName() . '</info>:');

            $registeredUsers = $event->getRegisteredUsers();
            if (empty($registeredUsers)) {
                $output->writeln('  No users registered.');
            } else {
                foreach ($registeredUsers as $user) {
                    $output->writeln(sprintf('  - Name: %s, Email: %s', $user->getName(), $user->getEmail()));
                }
            }
            $output->writeln('');
        }

        return Command::SUCCESS;
    }
}