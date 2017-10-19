<?php

class TOGoS_SimplerTest_TestCase
{
	protected $assertionCount;
	
	protected function assertTrue($whatever, $message="something should be true, but wasn't") {
		++$this->assertionCount;
		if( !$whatever ) {
			throw new TOGoS_SimplerTest_AssertionFailed($message);
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

	protected $expectedExceptionClass;
	public function expectException($expectedExceptionClass) {
		$this->expectedExceptionClass = $expectedExceptionClass;
	}

	protected function runSubTest2($meth) {
		$this->expectedExceptionClass = null;
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
		}
		if( $this->expectedExceptionClass ) {
			throw new TOGoS_SimplerTest_AssertionFailed("Expected #meth to throw {$this->expectedExceptionClass} but it never did :(");
		}
	}

	public function runSubTest($meth, array &$stats=array()) {
		$stats += array(
			'assertionCount' => 0,
			'subCaseCount' => 0,
			'failures' => array(),
		);		
		try {
			$this->assertionCount = 0;
			$this->runSubTest2($meth);
		} catch( Exception $e ) {
			$stats['failures'][] = array(
				'className' => get_class($this),
				'methodName' => $meth,
				'exception' => $e
			);
		}
		$stats['subCaseCount'] += 1;
		$stats['assertionCount'] += $this->assertionCount;
		return $stats;
	}
	
	public function runTests() {
		$subCaseCount = 0;
		$meths = get_class_methods(get_class($this));
		$stats = array();
		foreach( $meths as $meth ) {
			if( preg_match('/^test.*/', $meth) ) {
				$this->runSubTest($meth, $stats);
			}
		}
		return $stats;
	}
}
