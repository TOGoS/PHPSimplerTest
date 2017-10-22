<?php

/**
 * Due to the name, this won't get picked up by the test finder.
 * It has some sub-cases that purposely fail, so we can test that failures work.
 */
class TOGoS_SimplerTest_TestCaseTestCase extends TOGoS_SimplerTest_TestCase
{
	public function testTrivialThing() {
		$this->assertTrue(true);
	}
	public function testFail() {
		$this->assertTrue(false, "This test always fails, no matter what");
	}
	public function testExpectUnthrownException() {
		$this->expectException("Pood");
	}
}

class TOGoS_SimplerTest_TestCaseTest extends TOGoS_SimplerTest_TestCase
{
	public function testFailingAssertion() {
		$this->expectException('TOGoS_SimplerTest_AssertionFailed');
		$this->assertTrue(false);
	}
	public function testFailingEqualsAssertion() {
		$this->expectException('TOGoS_SimplerTest_AssertionFailed');
		$this->assertEquals(1, 2);
	}

	///

	public function testTrivialThing() {
		$otherTestCase = new TOGoS_SimplerTest_TestCaseTestCase();
		$results = $otherTestCase->runSubTest('testTrivialThing');
		$this->assertEquals( array(
			'assertionCount' => 1,
			'subCases' => array('TOGoS_SimplerTest_TestCaseTestCase#testTrivialThing'),
			'skippedSubCases' => array(),
			'failures' => array(),
		), $results );
	}
	
	public function testExpectUnthrownException() {
		$otherTestCase = new TOGoS_SimplerTest_TestCaseTestCase();
		$results = $otherTestCase->runSubTest('testExpectUnthrownException');
		$this->assertEquals( 1, count($results['failures']) );
	}
}
