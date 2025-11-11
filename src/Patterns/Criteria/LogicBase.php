<?php

namespace Neuron\Patterns\Criteria;

abstract class LogicBase extends Base
{
	protected $_otherCriteria;

	/**
	 * AndCriteria constructor.
	 * @param ICriteria $criteria
	 * @param ICriteria $otherCriteria
	 */

	public function __construct( ICriteria $criteria, ICriteria $otherCriteria )
	{
		$this->_criteria      = $criteria;
		$this->_otherCriteria = $otherCriteria;
	}
}
