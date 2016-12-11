<?php

namespace Abellion\Resolver\Tests;

use PHPUnit\Framework\TestCase;
use Abellion\Resolver\Resolver;

class ResolverTest extends TestCase
{

	public function testIsClass()
	{
		$this->assertTrue(Resolver::isClass(Resolver::class));
		$this->assertTrue(Resolver::isClass('Abellion\Resolver\Resolver'));

		/* From isFunction */
		$this->assertFalse(Resolver::isClass('strlen'));
		$this->assertFalse(Resolver::isClass(function() { }));
		/* From isMethod */
		$this->assertFalse(Resolver::isClass([$this, 'testIsClass']));
		$this->assertFalse(Resolver::isClass([Resolver::class, 'isClass']));
	}

	public function testIsMethod()
	{
		$this->assertTrue(Resolver::isMethod([$this, 'testIsClass']));
		$this->assertTrue(Resolver::isMethod([Resolver::class, 'isClass']));

		/* From isFunction */
		$this->assertFalse(Resolver::isMethod('strlen'));
		$this->assertFalse(Resolver::isMethod(function() { }));
		/* From isClass */
		$this->assertFalse(Resolver::isMethod(Resolver::class));
		$this->assertFalse(Resolver::isMethod('Abellion\Resolver\Resolver'));
	}

	public function testIsFunction()
	{
		$this->assertTrue(Resolver::isFunction('strlen'));
		$this->assertTrue(Resolver::isFunction(function() { }));

		/* From isMethod */
		$this->assertFalse(Resolver::isFunction([$this, 'testIsClass']));
		$this->assertFalse(Resolver::isFunction([Resolver::class, 'isClass']));
		/* From isClass */
		$this->assertFalse(Resolver::isFunction(Resolver::class));
		$this->assertFalse(Resolver::isFunction('Abellion\Resolver\Resolver'));
	}
}
