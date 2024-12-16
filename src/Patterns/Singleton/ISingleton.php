<?php

namespace Neuron\Patterns\Singleton;

/**
 * Singleton pattern.
 */
interface ISingleton
{
	/**
	 * Writes the object data to the storage medium.
	 */
	function serialize() : void;

	/**
	 * Clears the current global object.
	 */
	static function invalidate() : void;

	/**
	 * Gets the global object instance.
	 * @return mixed
	 */
	static function instance() : mixed;
}
