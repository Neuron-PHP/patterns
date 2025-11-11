<?php

namespace Neuron\Patterns\Command;

use Neuron\Core\Exceptions\CommandNotFound;

/**
 * Gets or creates a command object.
 */

class Factory
{
	private Cache $_cache;

	/**
	 * @param Cache $cache
	 */

	public function __construct( Cache $cache )
	{
		$this->_cache = $cache;
	}

	/**
	 * @param string $action
	 * @return ICommand
	 * @throws CommandNotFound
	 */

	public function get( string $action ): ICommand
	{
		return $this->_cache->get( $action );
	}
}
