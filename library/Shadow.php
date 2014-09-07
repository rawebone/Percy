<?php

namespace Rawebone\Percy;

/**
 * Shadow provides a quick method of computing the changes made on
 * an object.
 */
class Shadow
{
	protected $hashes = array();

	public function __construct($object)
	{
		foreach ($object as $property => $value) {
			$this->hashes[$property] = $this->hash($value);
		}
	}

	public function computeChanges($object)
	{
		$changes = array();

		foreach ($this->hashes as $property => $hash) {
			if ($this->hash($object->$property) !== $hash) {
				$changes[$property] = $object->$property;
			}
		}

		return $changes;
	}

	protected function hash($value)
	{
		return md5($value);
	}
}
