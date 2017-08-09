<?php

namespace Core\Helper\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

use \Core\Helper\ClassMaker;

class MakeRoutesCommand extends Command {
    private $classMaker = null;

    protected function configure() {
        $this->setName('app:make-routes')
             ->setDescription('Creates a new routes.')
             ->setHelp('This command allows you to create a routes for api.')
             ->addArgument()
             ->addArgument('className', InputArgument::REQUIRED, 'What are the class name?');
        
        $this->classMaker = new ClassMaker();
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        ConsoleUtil::writeStartExecute($output, 'Make Routes');
        $className = $input->getArgument('className');

        if ($className != '') { // verificar qual é o real retorno
            $success = $this->classMaker->$classMaker->makeRoutes($className);
        }

        ConsoleUtil::writeSuccess($output, $success);
    }
}