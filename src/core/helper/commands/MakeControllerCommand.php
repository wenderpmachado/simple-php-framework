<?php

namespace Core\Helper\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

use \Core\Helper\ClassMaker;

class MakeControllerCommand extends Command {
    private $classMaker = null;

    protected function configure() {
        $this->setName('app:make-controller')
             ->setDescription('Creates a new controller.')
             ->setHelp('This command allows you to create a controller.')
             ->addArgument('className', InputArgument::REQUIRED, 'What are the class name?')
             ->addArgument('parameters', InputArgument::IS_ARRAY | InputArgument::REQUIRED, 'What are the parameters?');
        
        $this->classMaker = new ClassMaker();
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        ConsoleUtil::writeStartExecute($output, 'Make Controller');
        $arguments = $input->getArgument('arguments');

        if (count($arguments) > 0) {
            $parameters = ConsoleUtil::argumentToParameters($arguments);
            $success = $this->classMaker->$classMaker->makeController($className, $parameters);
        }

        ConsoleUtil::writeSuccess($output, $success);
    }
}