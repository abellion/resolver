<?php

namespace Abellion\Resolver\Tests\Mocks;

class BParameter
{
	public $a;
	public $b;
	public $name;

	public function __construct(A $a, B $b, $name = 'Antoine')
	{
		$this->a = $a;
		$this->b = $b;
		$this->name = $name;
	}

	public function getA()
	{
		return $this->a;
	}
	public function getB()
	{
		return $this->b;
	}

	public function getName()
	{
		return $this->name;
	}
}
