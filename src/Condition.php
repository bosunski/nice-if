<?php

namespace Vendor\Package;

use Closure;

class Condition
{
	/**
	 * @var callable
	 */
	private $supposedAction;

	/**
	 * @var callable
	 */
	private $fallbackAction;

	/**
	 * @var mixed
	 */
	private $actualValue;

	/**
	 * @var mixed
	 */
	private $expectedValue;

	/**
	 * Return Value of last call, if any
	 * @var mixed
	 */
	private $lastReturn = null;

	/**
	 * Flags the call to the intended action
	 * @var bool
	 */
	private $supposedActionCalled = false;

	public function perform(callable $something)
	{
		$this->supposedAction = Closure::fromCallable($something);

		return $this;
	}

	public function once($value)
	{
		$this->actualValue = $value;
		return $this;
	}

	public function is($expected)
	{
		$this->expectedValue = $expected;
		$this->run();
		return $this;
	}

	/**
	 * Do something else
	 *
	 * @param callable $somethingElse
	 */
	public function otherwiseDo(callable $somethingElse)
	{
		$this->fallbackAction = Closure::fromCallable($somethingElse);

		$this->run();
	}

	public function call(callable $callable)
	{
		$this->lastReturn = call_user_func($callable);
	}

	/**
	 * Runs the Whole evaluation
	 */
	public function run()
	{
		if (!$this->supposedActionCalled && $this->actualValue === $this->expectedValue) {
			$this->call($this->supposedAction);

			$this->supposedActionCalled = true;
		} else {
			if ($this->fallbackAction instanceof Closure)
				$this->call( $this->fallbackAction );
		}
	}
}