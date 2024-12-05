<?php

namespace Neuron\Patterns\Singleton;

/**
 * Singleton pattern.
 */
interface ISingleton
{
	/**
	 * Writes the object data to the storage medium.
	 * @return mixed
	 */
	function serialize();

	/**
	 * Clears the current global object.
	 * @return mixed
	 */
	static function invalidate();

	/**
	 * Gets the global object instance.
	 * @return mixed
	 */
	static function instance();
}
