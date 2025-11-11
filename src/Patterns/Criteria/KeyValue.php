<?php

namespace Neuron\Patterns\Criteria;

class KeyValue extends Base implements ICriteria
{
	private $_key;
	private $_value;

	public function __construct( $key, $value )
	{
		$this->_key   = $key;
		$this->_value = $value;
	}

	public function meetCriteria( array $entities )
	{
		$results = [];

		foreach( $entities as $item )
		{
			if( $item[ $this->_key ] == $this->_value )
			{
				$results[] = $item;
			}
		}

		return $results;
	}
}
