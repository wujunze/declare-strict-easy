<?php

require_once __DIR__.'/../vendor/autoload.php';

use Dypa\DeclareStrictTypes\Command;
use Symfony\Component\Console\Application as ConsoleApplication;

$application = new ConsoleApplication(Command::DESCRIPTION, '1.0.0');
$command = new Command();
$application->add($command);
$application->setDefaultCommand($command->getName(), true);
$application->run();
