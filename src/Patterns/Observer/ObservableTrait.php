<?php
namespace Neuron\Patterns\Observer;

use Neuron\Data\ArrayHelper;
use Patterns\Observer\ObserveMe;

/**
 * Trait used to make an object observable.
 */

trait ObservableTrait
{
	private array $_Observers = [];

	/**
	 * Add an observer to the notification list.
	 * @param IObserver $Observer
	 * @return ObservableTrait|ObserveMe
	 */

	public function addObserver( IObserver $Observer ) : self
	{
		$this->_Observers[] = $Observer;
		return $this;
	}

	/**
	 * Remove an observer from the notification list.
	 * @param IObserver $Observer
	 */

	public function removeObserver( IObserver $Observer ): void
	{
		ArrayHelper::remove( $this->_Observers, $Observer );
	}

	/**
	 * @param mixed $params, ...
	 */

	public function notifyObservers( ...$params ): void
	{
		foreach( $this->_Observers as $Observer )
		{
			$Observer->observableUpdate( $this, ...$params );
		}
	}
}
