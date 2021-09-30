<?php

namespace Neuron\Patterns\Command;

interface ICommand
{
	/**
	 * @param CommandContext $context
	 * @return bool
	 */
	public function execute(CommandContext $context): bool;
}
