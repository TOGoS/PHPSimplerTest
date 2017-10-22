<?php

class TOGoS_SimplerTest_SkipThisTestDawg extends Exception { }

class TOGoS_SimplerTest_TestCase
{
	protected $assertionCount;

	protected function assertTrue($whatever, $message="something should be true, but wasn't") {
		++$this->assertionCount;
		if( !$whatever ) {
			throw new TOGoS_SimplerTest_AssertionFailed(
				(!empty($message) ? "{$message}: " : "")."Expected true but got ".var_export($whatever));
		}
	}
	
	protected function assertFalse($whatever, $message='') {
		++$this->assertionCount;
		if( $whatever ) {
			throw new TOGoS_SimplerTest_AssertionFailed(
				(!empty($message) ? "{$message}: " : "")."Expected false but got ".var_export($whatever));
		}
	}
	
	protected function assertEquals($expected, $actual, $message='') {
		++$this->assertionCount;
		if( $expected != $actual ) {
			throw new TOGoS_SimplerTest_AssertionFailed(
				(!empty($message) ? "{$message}: " : "").
				"Expected ".var_export($expected,true)." but got ".var_export($actual, true));
		}
	}
	
	protected function assertNotEquals($expected, $actual, $message='') {
		++$this->assertionCount;
		if( $expected == $actual ) {
			throw new TOGoS_SimplerTest_AssertionFailed(
				(!empty($message) ? "{$message}: " : "Oh no these things are equal but we expected them to be different: ").
				var_export($expected,true)." == ".var_export($actual, true));
		}
	}

	protected function assertNull($whatever, $message='') {
		++$this->assertionCount;
		if( $whatever !== null ) {
			throw new TOGoS_SimplerTest_AssertionFailed(
				(!empty($message) ? "{$message}: " : "")." Expected null but got ".var_export($whatever,true));
		}
	}
	
	protected function assertNotNull($whatever, $message='') {
		++$this->assertionCount;
		if( $whatever === null ) {
			throw new TOGoS_SimplerTest_AssertionFailed(
				(!empty($message) ? "{$message}: " : "")."Expected non-null but got null");
		}
	}

	protected function setUp() { }
	protected function tearDown() { }
	
	protected $expectedExceptionClass;
	public function expectException($expectedExceptionClass) {
		$this->expectedExceptionClass = $expectedExceptionClass;
	}
	
	protected function markTestSkipped() {
		throw new TOGoS_SimplerTest_SkipThisTestDawg();
	}
	
	protected function runSubTest2($meth) {
		$this->expectedExceptionClass = null;
		$this->setUp();
		try {
			$this->$meth();
		} catch( Exception $e ) {
			if( $this->expectedExceptionClass and $e instanceof $this->expectedExceptionClass ) {
				// Oh goodie!
				++$this->assertionCount;
				return;
			} else {
				throw $e;
			}
		} finally {
			$this->tearDown();
		}
		if( $this->expectedExceptionClass ) {
			throw new TOGoS_SimplerTest_AssertionFailed("Expected #meth to throw {$this->expectedExceptionClass} but it never did :(");
		}
	}
	
	public function runSubTest($meth, array &$stats=array()) {
		$stats += array(
			'assertionCount' => 0,
			'subCases' => array(),
			'skippedSubCases' => array(),
			'failures' => array(),
		);	
		$skipped = false;
		try {
			$this->assertionCount = 0;
			$this->runSubTest2($meth);
		} catch( TOGoS_SimplerTest_SkipThisTestDawg $sttd ) {
			$skipped = true;
		} catch( Exception $e ) {
			$stats['failures'][] = array(
				'className' => get_class($this),
				'methodName' => $meth,
				'exception' => $e
			);
		}
		$stats[$skipped ? 'skippedSubCases' : 'subCases'][] = get_class($this).'#'.$meth;
		$stats['assertionCount'] += $this->assertionCount;
		return $stats;
	}
	
	public function runTests() {
		$subCaseCount = 0;
		$meths = get_class_methods(get_class($this));
		$stats = array('testCases' => array(get_class($this)));
		foreach( $meths as $meth ) {
			if( preg_match('/^test.*/', $meth) ) {
				$this->runSubTest($meth, $stats);
			}
		}
		return $stats;
	}
}
