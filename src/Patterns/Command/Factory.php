<?php

namespace Neuron\Patterns\Command;

use Neuron\Core\Exceptions\CommandNotFound;

/**
 * Gets or creates a command object.
 */

class Factory
{
	private Cache $_Cache;

	/**
	 * @param Cache $Cache
	 */

	public function __construct( Cache $Cache )
	{
		$this->_Cache = $Cache;
	}

	/**
	 * @param string $Action
	 * @return ICommand
	 * @throws CommandNotFound
	 */

	public function get( string $Action ): ICommand
	{
		return $this->_Cache->get( $Action );
	}
}
