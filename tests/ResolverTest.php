<?php

namespace Abellion\Resolver\Tests;

use PHPUnit\Framework\TestCase;
use Abellion\Resolver\Resolver;

class ResolverTest extends TestCase
{
	public function __construct()
	{
	}

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
		$this->assertFalse(Resolver::isClass('Abellion\Resolver\Resolver::isClass'));
	}

	public function testIsMethod()
	{
		$this->assertTrue(Resolver::isMethod([$this, 'testIsClass']));
		$this->assertTrue(Resolver::isMethod([Resolver::class, 'isClass']));
		$this->assertTrue(Resolver::isMethod('Abellion\Resolver\Resolver::isClass'));

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
		$this->assertFalse(Resolver::isFunction('Abellion\Resolver\Resolver::isClass'));
		/* From isClass */
		$this->assertFalse(Resolver::isFunction(Resolver::class));
		$this->assertFalse(Resolver::isFunction('Abellion\Resolver\Resolver'));
	}

	public function testResolveClass()
	{
		$resolver = new Resolver;

		$this->assertInstanceOf(Resolver::class, $resolver->resolveClass(Resolver::class));
		$this->assertInstanceOf(ResolverTest::class, $resolver->resolveClass(ResolverTest::class));
	}
	public function testResolveClassWithDependenciesA()
	{
		$resolver = new Resolver;

		/* Test with one nested */
		$test1 = $resolver->resolveClass(Mocks\B::class);
		$this->assertInstanceOf(Mocks\B::class, $test1);
		$this->assertInstanceOf(Mocks\A::class, $test1->a);

		/* Test with two nested */
		$test2 = $resolver->resolveClass(Mocks\C::class);
		$this->assertInstanceOf(Mocks\C::class, $test2);
		$this->assertInstanceOf(Mocks\A::class, $test2->a);
		$this->assertInstanceOf(Mocks\B::class, $test2->b);
	}
	public function testResolveClassWithDependenciesB()
	{
		$resolver = new Resolver;
		$parametersA = ['name' => 'Antoine'];
		$parametersB = [2 => 'Antoine'];

		/* Test with named parameter */
		$test1 = $resolver->resolveClass(Mocks\AParameter::class, $parametersA);
		$this->assertInstanceOf(Mocks\AParameter::class, $test1);
		$this->assertInstanceOf(Mocks\A::class, $test1->a);
		$this->assertInstanceOf(Mocks\B::class, $test1->b);
		$this->assertEquals($test1->name, 'Antoine');

		/* Test with numbered parameter */
		$test2 = $resolver->resolveClass(Mocks\AParameter::class, $parametersB);
		$this->assertInstanceOf(Mocks\AParameter::class, $test2);
		$this->assertInstanceOf(Mocks\A::class, $test2->a);
		$this->assertInstanceOf(Mocks\B::class, $test2->b);
		$this->assertEquals($test2->name, 'Antoine');
	}
	public function testResolveClassWithDependenciesC()
	{
		$resolver = new Resolver;
		$parametersA = ['name' => 'Antoine Bellion'];
		$parametersB = [2 => 'Antoine Bellion'];

		/* Test without parameter */
		$test1 = $resolver->resolveClass(Mocks\BParameter::class);
		$this->assertInstanceOf(Mocks\BParameter::class, $test1);
		$this->assertInstanceOf(Mocks\A::class, $test1->a);
		$this->assertInstanceOf(Mocks\B::class, $test1->b);
		$this->assertEquals($test1->name, 'Antoine');

		/* Test with named parameter */
		$test1 = $resolver->resolveClass(Mocks\BParameter::class, $parametersA);
		$this->assertInstanceOf(Mocks\BParameter::class, $test1);
		$this->assertInstanceOf(Mocks\A::class, $test1->a);
		$this->assertInstanceOf(Mocks\B::class, $test1->b);
		$this->assertEquals($test1->name, 'Antoine Bellion');

		/* Test with numbered parameter */
		$test2 = $resolver->resolveClass(Mocks\BParameter::class, $parametersB);
		$this->assertInstanceOf(Mocks\BParameter::class, $test2);
		$this->assertInstanceOf(Mocks\A::class, $test2->a);
		$this->assertInstanceOf(Mocks\B::class, $test2->b);
		$this->assertEquals($test2->name, 'Antoine Bellion');
	}
}
