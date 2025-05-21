<?php

namespace Neuron\Patterns\Singleton;

/**
 * Singleton that serializes to memory.
 */

class Memory extends Base
{
	static $_instance = [];

	public function serialize(): void
	{
		static::$_instance[ get_called_class() ] = $this;
	}

	public static function invalidate(): void
	{
		unset( static::$_instance[ get_called_class() ] );
	}

	public static function instance(): mixed
	{
		if( !array_key_exists( get_called_class(), static::$_instance ) )
		{
			return null;
		}

		return static::$_instance[ get_called_class() ];
	}
}
