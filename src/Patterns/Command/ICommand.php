<?php

namespace Neuron\Patterns\Command;

interface ICommand
{
	/**
	 * @param CommandContext $Context
	 * @return bool
	 */
	public function execute(CommandContext $Context): bool;
}
