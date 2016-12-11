<?php

namespace Abellion\Resolver\Tests\Mocks;

class B
{
	public $a;

	public function __construct(A $a)
	{
		$this->a = $a;
	}

	public function getA()
	{
		return $this->a;
	}
}
