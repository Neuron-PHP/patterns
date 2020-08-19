<?php

namespace Neuron\Patterns\Criteria;

abstract class Base implements ICriteria
{
	protected $_Criteria;

	public function _and( ICriteria $OtherCriteria )
	{
		return new AndCriteria( $this, $OtherCriteria );
	}

	public function _or( ICriteria $OtherCriteria )
	{
		return new OrCriteria( $this, $OtherCriteria );
	}

	public function _not()
	{
		return new NotCriteria( $this );
	}
}
