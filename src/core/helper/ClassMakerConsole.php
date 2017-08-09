<?php

namespace Core\Helper;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClassMakerConsole extends Command {
	protected function configure() {
		 $this->setName('app:create-user')
		 	  ->setDescription('Creates a new user.')
		 	  ->setHelp('This command allows you to create a user...');
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		// ...
	}
}