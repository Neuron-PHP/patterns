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
		$session = new \Neuron\Data\Filter\Session();

		return $session->filterScalar( get_called_class() ) ?
			$session->filterScalar( get_called_class() )
			:
			false;
	}
}
