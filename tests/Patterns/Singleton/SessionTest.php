<?php

namespace Tests\Patterns\Singleton;

use PHPUnit\Framework\TestCase;
use Neuron\Patterns\Singleton\Session;

/**
 * Test concrete class extending Session for testing.
 */
class SessionSingletonTest extends Session
{
	public $testValue;
}

/**
 * Comprehensive tests for Session Singleton.
 *
 * Tests singleton pattern with PHP $_SESSION backend.
 */
class SessionTest extends TestCase
{
	protected function setUp(): void
	{
		parent::setUp();

		// Clear session for testing
		$_SESSION = [];
	}

	protected function tearDown(): void
	{
		// Clean up session
		$_SESSION = [];

		parent::tearDown();
	}

	public function testSerializeStoresObjectInSession(): void
	{
		$instance = new SessionSingletonTest();
		$instance->testValue = 'session_test_123';

		$instance->serialize();

		$this->assertArrayHasKey(SessionSingletonTest::class, $_SESSION);
		$this->assertSame($instance, $_SESSION[SessionSingletonTest::class]);
		$this->assertEquals('session_test_123', $_SESSION[SessionSingletonTest::class]->testValue);
	}

	public function testInvalidateRemovesFromSession(): void
	{
		// Store in session first
		$instance = new SessionSingletonTest();
		$instance->testValue = 'test';
		$_SESSION[SessionSingletonTest::class] = $instance;

		$this->assertArrayHasKey(SessionSingletonTest::class, $_SESSION);

		SessionSingletonTest::invalidate();

		$this->assertArrayNotHasKey(SessionSingletonTest::class, $_SESSION);
	}

	public function testInstanceReturnsStoredObjectWhenPresent(): void
	{
		// Check if Session filter class exists
		if (!class_exists('\Neuron\Data\Filter\Session')) {
			$this->markTestSkipped('Neuron Data component not available');
		}

		// Store in session first
		$instance = new SessionSingletonTest();
		$instance->testValue = 'stored_value';
		$_SESSION[SessionSingletonTest::class] = $instance;

		// Mock the Session filter
		$retrieved = SessionSingletonTest::instance();

		$this->assertNotFalse($retrieved);
	}

	public function testInstanceReturnsFalseWhenNotPresent(): void
	{
		// Check if Session filter class exists
		if (!class_exists('\Neuron\Data\Filter\Session')) {
			$this->markTestSkipped('Neuron Data component not available');
		}

		// Ensure nothing in session
		unset($_SESSION[SessionSingletonTest::class]);

		$retrieved = SessionSingletonTest::instance();

		$this->assertFalse($retrieved);
	}

	public function testSerializeAndRetrieve(): void
	{
		$instance = new SessionSingletonTest();
		$instance->testValue = 'roundtrip_test';

		// Serialize
		$instance->serialize();

		// Verify it's in session
		$this->assertArrayHasKey(SessionSingletonTest::class, $_SESSION);
		$this->assertEquals('roundtrip_test', $_SESSION[SessionSingletonTest::class]->testValue);
	}

	public function testInvalidateOnNonExistentSession(): void
	{
		// Should not throw exception when key doesn't exist
		unset($_SESSION[SessionSingletonTest::class]);

		SessionSingletonTest::invalidate();

		$this->assertArrayNotHasKey(SessionSingletonTest::class, $_SESSION);
	}

	public function testMultipleSerializeCalls(): void
	{
		$instance1 = new SessionSingletonTest();
		$instance1->testValue = 'first';
		$instance1->serialize();

		$this->assertEquals('first', $_SESSION[SessionSingletonTest::class]->testValue);

		$instance2 = new SessionSingletonTest();
		$instance2->testValue = 'second';
		$instance2->serialize();

		$this->assertEquals('second', $_SESSION[SessionSingletonTest::class]->testValue);
	}
}
