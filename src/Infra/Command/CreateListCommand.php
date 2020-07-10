<?php

namespace Infra\Command;

use Application\CreateList\CreateList;
use Application\CreateList\CreateListService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateListCommand extends Command
{
    protected static $defaultName = 'app:list:create';

    private CreateListService $createListService;

    public function __construct(CreateListService $createListService)
    {
        $this->createListService = $createListService;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'The name of the list.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        ($this->createListService)(new CreateList($name));

        $io = new SymfonyStyle($input, $output);
        $io->success(sprintf('The list %s has been created.', $name));

        return 0;
    }
}
