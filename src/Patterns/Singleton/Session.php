<?php

namespace Neuron\Patterns\Singleton;

/**
 * Class Session
 * @package Neuron\Patterns\Singleton
 *
 * Singleton that stores to a session.
 */
class Session extends Base
{
	public function serialize()
	{
		$_SESSION[ get_called_class() ] = $this;
	}

	public static function invalidate()
	{
		unset( $_SESSION[ get_called_class() ] );
	}

	public static function instance()
	{
		$Session = new \Neuron\Data\Filter\Session();

		return $Session->filterScalar( get_called_class() ) ?
			$Session->filterScalar( get_called_class() )
			:
			false;
	}
}
