<?php

namespace Neuron\Patterns\Singleton;

/**
 * Core singleton behaviour.
 */

abstract class Base implements ISingleton
{
	/**
	 * @return ISingleton|null
	 */
	public static function getInstance(): ?ISingleton
	{
		if( !static::instance() )
		{
			$Class = get_called_class();

			$Object = new $Class;
			$Object->serialize();
			return $Object;
		}

		$instance = static::instance();
		if( $instance instanceof self )
		{
			return static::instance();
		}

		return null;
	}

	public static abstract function instance() : mixed;
	public abstract function serialize() : void;
	public static abstract function invalidate() : void;
}
