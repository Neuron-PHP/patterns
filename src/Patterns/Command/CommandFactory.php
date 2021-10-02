<?php

namespace Neuron\Patterns\Command;

class CommandFactory
{
	/**
	 * @var ICommandCache
	 */
	private ICommandCache $_Cache;

	/**
	 * @param ICommandCache $Cache
	 */
	public function __construct(ICommandCache $Cache)
	{
		$this->_Cache = $Cache;
	}

	/**
	 * @param string $Action
	 * @return ICommand
	 */
	public function getCommand(string $Action): ICommand
	{
		return $this->_Cache->get($Action);
	}
}
