<?php

namespace Neuron\Patterns\Observer;

/**
 * Interface to allow an observable object to notify an observer.
 */

interface IObserver
{
	/**
	 * @param $observable
	 * @param $param
	 * @return mixed
	 */

	function observableUpdate( $observable, ...$param );
}
