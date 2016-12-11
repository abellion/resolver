<?php

namespace Abellion\Resolver\Tests;

use ReflectionClass;
use ReflectionMethod;
use ReflectionFunction;

use PHPUnit\Framework\TestCase;
use Abellion\Resolver\Resolver;

class ResolverTest extends TestCase
{
	public function __construct()
	{
	}

	/**
	 * Types detection
	 */

	public function testIsClass()
	{
		$this->assertTrue(Resolver::isClass(Mocks\A::class));
		$this->assertTrue(Resolver::isClass('Abellion\Resolver\Tests\Mocks\A'));

		/* From isFunction */
		$this->assertFalse(Resolver::isClass('strlen'));
		$this->assertFalse(Resolver::isClass(function() { }));
		/* From isMethod */
		$this->assertFalse(Resolver::isClass([Mocks\A::class, 'getName']));
		$this->assertFalse(Resolver::isClass('Abellion\Resolver\Tests\Mocks\A::getName'));
	}
	public function testIsMethod()
	{
		$this->assertTrue(Resolver::isMethod([Mocks\A::class, 'getName']));
		$this->assertTrue(Resolver::isMethod('Abellion\Resolver\Tests\Mocks\A::getName'));

		/* From isFunction */
		$this->assertFalse(Resolver::isMethod('strlen'));
		$this->assertFalse(Resolver::isMethod(function() { }));
		/* From isClass */
		$this->assertFalse(Resolver::isMethod(Mocks\A::class));
		$this->assertFalse(Resolver::isMethod('Abellion\Resolver\Tests\Mocks\A'));
	}
	public function testIsFunction()
	{
		$this->assertTrue(Resolver::isFunction('strlen'));
		$this->assertTrue(Resolver::isFunction(function() { }));

		/* From isMethod */
		$this->assertFalse(Resolver::isFunction([Mocks\A::class, 'getName']));
		$this->assertFalse(Resolver::isFunction('Abellion\Resolver\Tests\Mocks\A::getName'));
		/* From isClass */
		$this->assertFalse(Resolver::isFunction(Mocks\A::class));
		$this->assertFalse(Resolver::isFunction('Abellion\Resolver\Tests\Mocks\A'));
	}

	/**
	 * Parameters resolver
	 *
	 * I'm testing by resolving classes because the implementation should use the
	 * "resolveParameters" method to resolve the parameters from classes, methods or functions
	 */

	public function testResolveParametersA()
	{
		$resolver = new Resolver;

		/* Test with one nested */
		$test = $resolver->resolveClass(Mocks\B::class);
		$this->assertInstanceOf(Mocks\B::class, $test);
		$this->assertInstanceOf(Mocks\A::class, $test->a);

		/* Test with two nested */
		$test = $resolver->resolveClass(Mocks\C::class);
		$this->assertInstanceOf(Mocks\C::class, $test);
		$this->assertInstanceOf(Mocks\A::class, $test->a);
		$this->assertInstanceOf(Mocks\B::class, $test->b);
	}
	public function testResolveParametersB()
	{
		$resolver = new Resolver;
		$parametersA = ['name' => 'Antoine'];
		$parametersB = [2 => 'Antoine'];

		/* Test with named parameter */
		$test = $resolver->resolveClass(Mocks\AParameter::class, $parametersA);
		$this->assertInstanceOf(Mocks\AParameter::class, $test);
		$this->assertInstanceOf(Mocks\A::class, $test->a);
		$this->assertInstanceOf(Mocks\B::class, $test->b);
		$this->assertEquals($test->name, 'Antoine');

		/* Test with numbered parameter */
		$test = $resolver->resolveClass(Mocks\AParameter::class, $parametersB);
		$this->assertInstanceOf(Mocks\AParameter::class, $test);
		$this->assertInstanceOf(Mocks\A::class, $test->a);
		$this->assertInstanceOf(Mocks\B::class, $test->b);
		$this->assertEquals($test->name, 'Antoine');
	}
	public function testResolveParametersC()
	{
		$resolver = new Resolver;
		$parametersA = ['name' => 'Antoine Bellion'];
		$parametersB = [2 => 'Antoine Bellion'];

		/* Test without parameter */
		$test = $resolver->resolveClass(Mocks\BParameter::class);
		$this->assertInstanceOf(Mocks\BParameter::class, $test);
		$this->assertInstanceOf(Mocks\A::class, $test->a);
		$this->assertInstanceOf(Mocks\B::class, $test->b);
		$this->assertEquals($test->name, 'Antoine');

		/* Test with named parameter */
		$test = $resolver->resolveClass(Mocks\BParameter::class, $parametersA);
		$this->assertInstanceOf(Mocks\BParameter::class, $test);
		$this->assertInstanceOf(Mocks\A::class, $test->a);
		$this->assertInstanceOf(Mocks\B::class, $test->b);
		$this->assertEquals($test->name, 'Antoine Bellion');

		/* Test with numbered parameter */
		$test = $resolver->resolveClass(Mocks\BParameter::class, $parametersB);
		$this->assertInstanceOf(Mocks\BParameter::class, $test);
		$this->assertInstanceOf(Mocks\A::class, $test->a);
		$this->assertInstanceOf(Mocks\B::class, $test->b);
		$this->assertEquals($test->name, 'Antoine Bellion');
	}
	public function testResolveParametersD()
	{
		$resolver = new Resolver;
		$parametersA = ['name' => 'Antoine Bellion', 'age' => 20];
		$parametersB = [1 => 'Antoine Bellion', 3 => 20];
		$parametersC = ['age' => 20];
		$parametersD = [3 => 20];

		/* Test with named parameter */
		$test = $resolver->resolveClass(Mocks\CParameter::class, $parametersA);
		$this->assertInstanceOf(Mocks\CParameter::class, $test);
		$this->assertInstanceOf(Mocks\A::class, $test->a);
		$this->assertInstanceOf(Mocks\B::class, $test->b);
		$this->assertEquals($test->age, 20);
		$this->assertEquals($test->name, 'Antoine Bellion');

		/* Test with numbered parameter */
		$test = $resolver->resolveClass(Mocks\CParameter::class, $parametersB);
		$this->assertInstanceOf(Mocks\CParameter::class, $test);
		$this->assertInstanceOf(Mocks\A::class, $test->a);
		$this->assertInstanceOf(Mocks\B::class, $test->b);
		$this->assertEquals($test->age, 20);
		$this->assertEquals($test->name, 'Antoine Bellion');

		/* Test with one named parameter */
		$test = $resolver->resolveClass(Mocks\CParameter::class, $parametersC);
		$this->assertInstanceOf(Mocks\CParameter::class, $test);
		$this->assertInstanceOf(Mocks\A::class, $test->a);
		$this->assertInstanceOf(Mocks\B::class, $test->b);
		$this->assertEquals($test->age, 20);
		$this->assertEquals($test->name, 'Antoine');

		/* Test with one numbered parameter */
		$test = $resolver->resolveClass(Mocks\CParameter::class, $parametersC);
		$this->assertInstanceOf(Mocks\CParameter::class, $test);
		$this->assertInstanceOf(Mocks\A::class, $test->a);
		$this->assertInstanceOf(Mocks\B::class, $test->b);
		$this->assertEquals($test->age, 20);
		$this->assertEquals($test->name, 'Antoine');
	}

	/**
	 * Reflector getters
	 */
	public function testGetReflector()
	{
		$this->assertInstanceOf(ReflectionClass::class, Resolver::getReflector(Mocks\A::class));
		$this->assertInstanceOf(ReflectionMethod::class, Resolver::getReflector([Mocks\A::class, 'getName']));
		$this->assertInstanceOf(ReflectionFunction::class, Resolver::getReflector('strlen'));
	}
	public function testGetClassReflector()
	{
		$this->assertInstanceOf(ReflectionClass::class, Resolver::getClassReflector(Mocks\A::class));
	}
	public function testGetMethodReflector()
	{
		$this->assertInstanceOf(ReflectionMethod::class, Resolver::getMethodReflector([Mocks\A::class, 'getName']));
		$this->assertInstanceOf(ReflectionMethod::class, Resolver::getMethodReflector('Abellion\Resolver\Tests\Mocks\A::getName'));
	}
	public function testGetFunctionReflector()
	{
		$this->assertInstanceOf(ReflectionFunction::class, Resolver::getFunctionReflector('strlen'));
	}

	/**
	 * General resolvers
	 */

	public function testResolve()
	{
		$resolver = new Resolver;

		$this->assertInstanceOf(Mocks\A::class, $resolver->resolve(Mocks\A::class));
		$this->assertEquals('Antoine', $resolver->resolve([Mocks\A::class, 'getName']));
		$this->assertEquals($resolver->resolve('time'), time());
	}
	public function testResolveClass()
	{
		$resolver = new Resolver;
		$parametersA = ['name' => 'Antoine'];

		$this->assertInstanceOf(Mocks\A::class, $resolver->resolveClass(Mocks\A::class));
		$this->assertInstanceOf(Mocks\A::class, $resolver->resolveClass('Abellion\Resolver\Tests\Mocks\A'));

		/* With parameter */
		$this->assertInstanceOf(Mocks\AParameter::class, $resolver->resolveClass(Mocks\AParameter::class, $parametersA));
	}
	public function testResolveMethod()
	{
		$resolver = new Resolver;

		$this->assertEquals('Antoine', $resolver->resolveMethod([Mocks\A::class, 'getName']));
		$this->assertEquals('Antoine', $resolver->resolveMethod('Abellion\Resolver\Tests\Mocks\A::getName'));

		/* With parameter */
		$this->assertEquals('Antoine', $resolver->resolveMethod([Mocks\A::class, 'getParameter'], ['Antoine']));
	}
	public function testResolveFunction()
	{
		$resolver = new Resolver;

		$this->assertEquals($resolver->resolveFunction('time'), time());
		$this->assertEquals($resolver->resolveFunction(function() {
			return 'Antoine';
		}), 'Antoine');

		/* With parameter */
		$this->assertEquals($resolver->resolveFunction('strlen', ['Antoine']), 7);
	}
}
