<?php
namespace Neuron\Patterns;

/**
 * Interface to add runnable behavior to an object.
 */

interface IRunnable
{
	/**
	 * Generic run method.
	 * @param array $Argv
	 * @return mixed
	 */
	public function run( array $Argv = [] );
}
