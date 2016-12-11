<?php

namespace Abellion\Resolver\Tests\Mocks;

class C
{
	public $a;
	public $b;

	public function __construct(A $a, B $b)
	{
		$this->a = $a;
		$this->b = $b;
	}

	public function getA()
	{
		return $this->a;
	}
	public function getB()
	{
		return $this->b;
	}

}
