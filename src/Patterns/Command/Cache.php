<?php

namespace Neuron\Patterns\Command;

use Neuron\Core\Exceptions\CommandNotFound;
use Neuron\Patterns\Singleton\Memory as Singleton;

/**
 * Maps strings to command classes.
 */

class Cache extends Singleton
{
	private static array $_cache = [];

	/**
	 * @param string $action
	 * @param string $command
	 * @return Cache
	 */

	public function set( string $action, string $command ): Cache
	{
		self::$_cache[ $action ] = $command;

		return self::instance();
	}

	/**
	 * @param string $action
	 * @return ICommand
	 * @throws CommandNotFound
	 */

	public function get( string $action ): ICommand
	{
		if( !isset( self::$_cache[ $action ] ) )
		{
			throw new CommandNotFound( "No command found for action '{$action}' in the cache." );
		}

		return new self::$_cache[ $action ];
	}
}
