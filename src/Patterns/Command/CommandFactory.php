<?php

namespace Neuron\Patterns\Command;

class CommandFactory
{
	/**
	 * @var ICommandCache
	 */
	private ICommandCache $commandCache;

	/**
	 * @param ICommandCache $cache
	 */
	public function __construct(ICommandCache $cache)
	{
		$this->commandCache = $cache;
	}

	/**
	 * @param string $action
	 * @return ICommand
	 */
	public function getCommand(string $action): ICommand
	{
		return $this->commandCache->get($action);
	}
}
