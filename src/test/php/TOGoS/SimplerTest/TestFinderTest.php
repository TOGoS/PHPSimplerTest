<?php

class TOGoS_SimplerTest_TestFinderTest extends TOGoS_SimplerTest_TestCase
{
	public function testFindSomeTests() {
		$finder = new TOGoS_SimplerTest_TestFinder();
		$testClasses = $finder->findTestClasses("src/test/php");
		ksort($testClasses);
		$this->assertEquals(array(
			'TOGoS_SimplerTest_TestCaseTest' => array(
				'className' => 'TOGoS_SimplerTest_TestCaseTest',
				'filename' => 'src/test/php/TOGoS/SimplerTest/TestCaseTest.php',
			),
			'TOGoS_SimplerTest_TestFinderTest' => array(
				'className' => 'TOGoS_SimplerTest_TestFinderTest',
				'filename' => 'src/test/php/TOGoS/SimplerTest/TestFinderTest.php',
			),
		), $testClasses, "List of test classes ain't right");
	}
}
