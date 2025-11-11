<?php
namespace Neuron\Patterns\Observer;

use Neuron\Data\ArrayHelper;
use Patterns\Observer\ObserveMe;

/**
 * Trait used to make an object observable.
 */

trait ObservableTrait
{
	private array $_observers = [];

	/**
	 * Add an observer to the notification list.
	 * @param IObserver $observer
	 * @return ObservableTrait|ObserveMe
	 */

	public function addObserver( IObserver $observer ) : self
	{
		$this->_observers[] = $observer;
		return $this;
	}

	/**
	 * Remove an observer from the notification list.
	 * @param IObserver $observer
	 */

	public function removeObserver( IObserver $observer ): void
	{
		ArrayHelper::remove( $this->_observers, $observer );
	}

	/**
	 * @param mixed $params, ...
	 */

	public function notifyObservers( ...$params ): void
	{
		foreach( $this->_observers as $observer )
		{
			$observer->observableUpdate( $this, ...$params );
		}
	}
}
