<?php

namespace Tests\Patterns\Criteria;

use \Neuron\Patterns\Criteria\KeyValue;
use \Neuron\Data\ArrayHelper;
use PHPUnit\Framework\TestCase;

class CriteriaTest extends TestCase
{
	public function testAndCriteria()
	{
		$aTest = [
			[
				'name' => 'one',
				'type' => 1
			],
			[
				'name' => 'two',
				'type' => 2
			],
			[
				'name' => 'three',
				'type' => 1
			],
		];

		$matchesOne = new KeyValue( 'type', '1' );
		$matchesTwo = new KeyValue( 'name', 'three' );

		$aResult = $matchesOne->_and( $matchesTwo )->meetCriteria( $aTest );

		$this->assertEquals( 1, count( $aResult ) );

		$this->assertTrue(
			ArrayHelper::contains( $aResult[ 0 ], 'three', 'name' )
		);
	}

	public function testAndCriteriaReturnsEmptyWhenFirstCriteriaMatchesNothing()
	{
		$aTest = [
			[
				'name' => 'one',
				'type' => 1
			],
			[
				'name' => 'two',
				'type' => 2
			],
		];

		// First criteria will match nothing
		$matchesNothing = new KeyValue( 'type', '999' );
		$matchesSomething = new KeyValue( 'name', 'one' );

		$aResult = $matchesNothing->_and( $matchesSomething )->meetCriteria( $aTest );

		// Should return empty array immediately
		$this->assertEquals( 0, count( $aResult ) );
		$this->assertEmpty( $aResult );
	}

	public function testOrCriteria()
	{
		$aTest = [
			[
				'name' => 'one',
				'type' => 1
			],
			[
				'name' => 'two',
				'type' => 2
			],
			[
				'name' => 'three',
				'type' => 3
			],
		];

		$matchesOne = new KeyValue( 'type', '1' );
		$matchesTwo = new KeyValue( 'name', 'three' );

		$aResult = $matchesOne->_or( $matchesTwo )->meetCriteria( $aTest );

		$this->assertEquals( 2, count( $aResult ) );

		$this->assertTrue(
			ArrayHelper::contains( $aResult[ 1 ], 'three', 'name' )
		);

		$this->assertTrue(
			ArrayHelper::contains( $aResult[ 0 ], '1', 'type' )
		);

	}

	public function testNotCriteria()
	{
		$aTest = [
			[
				'name' => 'one',
				'type' => 1
			],
			[
				'name' => 'two',
				'type' => 2
			],
			[
				'name' => 'Fred',
				'type' => 1
			],
		];

		$matchesOne  = new KeyValue( 'type', '1' );
		$matchesFred = new KeyValue( 'name', 'Fred' );

		$aResult = $matchesOne->_and( $matchesFred->_not() )->meetCriteria( $aTest );

		$this->assertEquals( 1, count( $aResult ) );

		$this->assertTrue(
			ArrayHelper::contains( $aResult[ 0 ], 'one', 'name' )
		);

		$this->assertFalse(
			ArrayHelper::contains( $aResult, 'Fred', 'name' )
		);

	}
}
