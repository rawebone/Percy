<?php

namespace Rawebone\Percy\Tests;

use Rawebone\Percy as p;

class UtilityFunctionTests extends \PHPUnit_Framework_TestCase
{
	public function testSnakeCase()
	{
		$this->assertEquals("blah", p\snake("blah"));
		$this->assertEquals("blah", p\snake("Blah"));
		$this->assertEquals("blah_blah", p\snake("BlahBlah"));
	}

	public function testLast()
	{
		$set = array("a", "b", "c");
		$this->assertEquals("c", p\last($set));
	}

	public function testLastWithEmptyArray()
	{
		$this->assertEquals(null, p\last(array()));
	}

	public function testFirst()
	{
		$set = array("a", "b", "c");
		$this->assertEquals("a", p\first($set));
	}

	public function testFirstWithEmptyArray()
	{
		$this->assertEquals(null, p\first(array()));
	}
}
