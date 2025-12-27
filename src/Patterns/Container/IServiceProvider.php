<?php

namespace Neuron\Patterns\Container;

/**
 * Service provider interface
 *
 * Service providers are responsible for registering bindings
 * and singletons with the container.
 *
 * @package Neuron\Patterns\Container
 */
interface IServiceProvider
{
	/**
	 * Register services in the container
	 *
	 * @param IContainer $container
	 * @return void
	 */
	public function register( IContainer $container ): void;
}
