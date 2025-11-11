<?php

namespace Patterns\Observer;

use Neuron\Patterns\Observer\IObserver;
use Neuron\Patterns\Observer\ObservableTrait;
use PHPUnit\Framework\TestCase;

class ObserveMe
{
	use ObservableTrait;

	public function doIt()
	{
		$this->notifyObservers( 1 );
	}
}

class Observer implements IObserver
{
	public $State = 0;

	public function observableUpdate( $observable, ...$param )
	{
		$this->State = $param[ 0 ];
	}
}

class ObserverTest extends TestCase
{
	/**
	 * If notification works correctly then
	 * the observer will set its state variable to that
	 * passed by the observable.
	 */
	public function testObserver()
	{
		$observable = new ObserveMe();
		$observer   = new Observer();

		$observable->addObserver( $observer );

		$observable->doIt();;

		$this->assertEquals(
			1,
			$observer->State
		);
	}

	/**
	 * Removing the observer after adding it will
	 * prevent its state from being updated.
	 */
	public function testRemoveObserver()
	{
		$observable = new ObserveMe();
		$observer   = new Observer();

		$observable->addObserver( $observer );
		$observable->removeObserver( $observer );

		$observable->doIt();;

		$this->assertEquals(
			0,
			$observer->State
		);
	}
}

