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
		if( static::instance() )
		{
			$instance = static::instance();
			if( $instance instanceof self )
			{
				return static::instance();
			}
		}
		else
		{
			$sClass = get_called_class();

			$obj = new $sClass;
			$obj->serialize();
			return $obj;
		}

		return null;
	}
}
