<?php

namespace Infra\ItemList\Command;

use Application\ItemList\CreateList\CreateList;
use Application\ItemList\CreateList\CreateListUsecase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateListCommand extends Command
{
    protected static $defaultName = 'app:list:create';

    private CreateListUsecase $createListUsecase;

    public function __construct(CreateListUsecase $createListUsecase)
    {
        $this->createListUsecase = $createListUsecase;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'The name of the list.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        ($this->createListUsecase)(new CreateList($name));

        $io = new SymfonyStyle($input, $output);
        $io->success(sprintf('The list %s has been created.', $name));

        return 0;
    }
}
