<?php

namespace Abellion\Resolver\Tests\Mocks;

class CParameter
{
	public $a;
	public $b;
	public $age;
	public $name;

	public function __construct(A $a, $name = 'Antoine', B $b, $age)
	{
		$this->a = $a;
		$this->b = $b;
		$this->age = $age;
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

	public function getAge()
	{
		return $this->age;
	}
	public function getName()
	{
		return $this->name;
	}

}
