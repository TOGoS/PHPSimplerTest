<?php

class TOGoS_SimplerTest_TestFinder
{
	public function findTestClassesInFile( $file, array &$into=array() ) {
		$fh = fopen($file, 'r');
		if( $fh === false ) {
			throw new Exception("Failed to open $file!!!");
		}
		while( ($line = fgets($fh)) !== false ) {
			if( preg_match('/^\s*class\s*(\S*Test)\b/',$line,$bif) ) {
				$into[$bif[1]] = array(
					'className' => $bif[1],
					'filename' => $file,
				);
			}
		}
		fclose($fh);
		return $into;
	}
	
	public function findTestClasses( $path, array &$into=array() ) {
		if( is_file($path) && preg_match('/Test\.php$/', $path) ) {
			$this->findTestClassesInFile($path, $into);
		} else if( is_dir($path) ) {
			$dh = opendir($path);
			while( ($fn = readdir($dh)) !== false ) {
				if( $fn[0] == '.' ) continue;
				$sp = "{$path}/{$fn}";
				$this->findTestClasses($sp, $into);
			}
			closedir($dh);
		}
		return $into;
	}
}
