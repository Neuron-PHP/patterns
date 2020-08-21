<?php

namespace Neuron\Patterns\Singleton;

/**
 * Interface ISingleton
 * @package Neuron\Patterns\Singleton
 *
 * Interface for singleton functionality.
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
