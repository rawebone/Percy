<?php

namespace Rawebone\Percy\Tests;

use Rawebone\Percy\Shadow;

class ShadowTest extends \PHPUnit_Framework_TestCase
{
	function testComputeChanges()
	{
		$object = (object)array("id" => 1);
		$shadow = new Shadow($object);

		$object->id = 2;
		$changes = $shadow->computeChanges($object);

		$this->assertArrayHasKey("id", $changes);
		$this->assertEquals(2, $changes["id"]);
	}
}
