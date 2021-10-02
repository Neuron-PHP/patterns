<?php

namespace Tests\Mock;

use Neuron\Patterns\Command\CommandContext;
use Neuron\Patterns\Command\ICommand;

class MockCommand implements ICommand
{
	public function execute(CommandContext $Context): bool
	{
		return $Context->getParam('action') === 'mock';
	}
}
