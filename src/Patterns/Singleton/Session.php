<?php

namespace Neuron\Patterns\Singleton;

/**
 * Singleton that serializes to a session.
 */
class Session extends Base
{
	public function serialize(): void
	{
		$_SESSION[ get_called_class() ] = $this;
	}

	public static function invalidate(): void
	{
		unset( $_SESSION[ get_called_class() ] );
	}

	public static function instance(): mixed
	{
		$Session = new \Neuron\Data\Filter\Session();

		return $Session->filterScalar( get_called_class() ) ?
			$Session->filterScalar( get_called_class() )
			:
			false;
	}
}
