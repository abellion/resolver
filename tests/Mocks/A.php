<?php

namespace Abellion\Resolver\Tests\Mocks;

class A
{
	public static function getName()
	{
		return 'Antoine';
	}
	public static function getParameter($parameter)
	{
		return $parameter;
	}

}
