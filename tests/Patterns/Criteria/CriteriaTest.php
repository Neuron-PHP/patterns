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

		$MatchesOne = new KeyValue( 'type', '1' );
		$MatchesTwo = new KeyValue( 'name', 'three' );

		$aResult = $MatchesOne->_and( $MatchesTwo )->meetCriteria( $aTest );

		$this->assertEquals( 1, count( $aResult ) );

		$this->assertTrue(
			ArrayHelper::contains( $aResult[ 0 ], 'three', 'name' )
		);
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

		$MatchesOne = new KeyValue( 'type', '1' );
		$MatchesTwo = new KeyValue( 'name', 'three' );

		$aResult = $MatchesOne->_or( $MatchesTwo )->meetCriteria( $aTest );

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

		$MatchesOne  = new KeyValue( 'type', '1' );
		$MatchesFred = new KeyValue( 'name', 'Fred' );

		$aResult = $MatchesOne->_and( $MatchesFred->_not() )->meetCriteria( $aTest );

		$this->assertEquals( 1, count( $aResult ) );

		$this->assertTrue(
			ArrayHelper::contains( $aResult[ 0 ], 'one', 'name' )
		);

		$this->assertFalse(
			ArrayHelper::contains( $aResult, 'Fred', 'name' )
		);

	}
}
