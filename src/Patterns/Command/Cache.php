<?php

namespace Neuron\Patterns\Command;

use Neuron\Core\Exceptions\CommandNotFound;
use Neuron\Patterns\Singleton\Memory as Singleton;

/**
 * Maps strings to command classes.
 */

class Cache extends Singleton
{
	private static array $_Cache = [];

	/**
	 * @param string $Action
	 * @param string $Command
	 * @return Cache
	 */

	public function set( string $Action, string $Command ): Cache
	{
		self::$_Cache[ $Action ] = $Command;

		return self::instance();
	}

	/**
	 * @param string $Action
	 * @return ICommand
	 * @throws CommandNotFound
	 */

	public function get( string $Action ): ICommand
	{
		if( !isset( self::$_Cache[ $Action ] ) )
		{
			throw new CommandNotFound( "No command found for action '{$Action}' in the cache." );
		}

		return new self::$_Cache[ $Action ];
	}
}
