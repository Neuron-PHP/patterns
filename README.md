[![Build Status](https://travis-ci.org/neuron-php/neuron.svg?branch=master)](https://travis-ci.com/neuron-php/patterns)

# Neuron-PHP Patterns 

## Overview


## Installation

Install php composer from https://getcomposer.org/

Install the neuron patterns component:

    composer require neuron-php/patterns


## Patterns

### Criteria

### Observer

An ObserverableTrait and IObserver interface make up the Observer pattern implementation.

    class Observable
    {
        use ObservableTrait;
        
        public function updated()
        {
            $this->notifyObservers( 1, 2, 3 );
        }
    }
    
    class Observer implements IObserver
    {
        public $State = 0;
        
        // IObserver implementation method..
        
        public function observableUpdate( $Observable, ...$param )
        {
            // Will set $State to 1.
            
        	$this->State = $param[ 0 ];
        }
    }

    $Observer   = new Observer;
    $Observable = new Observable;
    
    $Observable->addObserver( $Observer );
    
    $Observable->updated();
    
    // Will notify all attached observers..
    
    // Later on, clean up..
    
    $Observable->removeObserver( $Observer );
    
### Registry

### Singleton

# More Information

You can read more about the Neuron components at [neuronphp.com](http://neuronphp.com)
