<?php

namespace Neuron\Patterns\Criteria;

abstract class LogicBase extends Base
{
	protected $_OtherCriteria;

	/**
	 * AndCriteria constructor.
	 * @param ICriteria $Criteria
	 * @param ICriteria $OtherCriteria
	 */

	public function __construct( ICriteria $Criteria, ICriteria $OtherCriteria )
	{
		$this->_Criteria      = $Criteria;
		$this->_OtherCriteria = $OtherCriteria;
	}
}
