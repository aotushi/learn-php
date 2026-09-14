<?php

declare(strict_types=1);

namespace Acme\Learning\Console\Command;

use Acme\Learning\Model\Greeting;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class GreetingCommand extends Command
{
    private const NAME_OPTION = 'name';

    public function __construct(private readonly Greeting $greeting)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('learning:greet')
            ->setDescription('Verify that the Acme_Learning module is active.')
            ->addOption(self::NAME_OPTION, null, InputOption::VALUE_REQUIRED, 'Name to greet');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getOption(self::NAME_OPTION);
        $output->writeln($this->greeting->forName(is_string($name) ? $name : ''));

        return Command::SUCCESS;
    }
}
