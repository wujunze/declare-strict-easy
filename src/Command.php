<?php

declare(strict_types=1);

namespace Dypa\DeclareStrictTypes;

use Dypa\DeclareStrictTypes\Strategy\Add;
use Dypa\DeclareStrictTypes\Strategy\Remove;
use Symfony\Component\Console\Command\Command as ConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends ConsoleCommand
{
    const DESCRIPTION = 'PHP7 tool for easy add/remove "declare(strict_types=1)"';

    const MODE_ADD = 'add';
    const MODE_REMOVE = 'remove';

    protected function configure()
    {
        $this->setName('declare_strict_types');
        $this->setDescription(self::DESCRIPTION);
        $this->setHelp('TODO');

        $this->addArgument(
            'mode',
            InputArgument::REQUIRED,
            'Add/remove strict mode'
        );

        $this->addArgument(
            'include',
            InputArgument::IS_ARRAY | InputArgument::REQUIRED,
            'Which directories must be changed?'
        );

        $this->addOption(
            'exclude',
            'e',
            InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
            'Which directories must be excluded?'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mode = $input->getArgument('mode');
        $includeDirectories = $input->getArgument('include');
        $excludeDirectories = $input->getOption('exclude');

        if (!in_array($mode, [self::MODE_ADD, self::MODE_REMOVE])) {
            throw new \InvalidArgumentException('You must chose between add and remove mode');
        }
        if (count($includeDirectories) == 0) {
            throw new \InvalidArgumentException('You must provide at least one folder or file');
        }

        if ($mode == self::MODE_ADD) {
            $replaceStrategy = new Add();
        } elseif ($mode == self::MODE_REMOVE) {
            $replaceStrategy = new Remove();
        }

        $replacer = new FileSystemReplacer($includeDirectories, $excludeDirectories);
        $replacer->replace($replaceStrategy);

        $output->writeln($replacer->getAffectedFiles(), OutputInterface::VERBOSITY_VERBOSE);

        $output->writeln([
            'Number of changed files: '.count($replacer->getAffectedFiles()),
            '<info>All done</info>, do not forget run your tests!',
        ]);
    }
}
