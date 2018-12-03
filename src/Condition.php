<?php

/*
 * This file is part of <package name>.
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Neater\Condition;

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
	 * Return Value of last call, if any.
	 *
	 * @var mixed
	 */
	private $lastReturn = null;

	/**
	 * Flags the call to the intended action.
	 *
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

		if ($this->lastReturn) {
			return $this->lastReturn;
		}

		return $this;
	}

	/**
	 * Do something else.
	 *
	 * @param callable $somethingElse
	 *
	 * @return mixed
	 */
	public function otherwiseDo(callable $somethingElse)
	{
		$this->fallbackAction = Closure::fromCallable($somethingElse);

		$this->run();

		if ($this->lastReturn) {
			return $this->lastReturn;
		}
	}

	public function call(callable $callable)
	{
		$this->lastReturn = call_user_func($callable);
	}

	/**
	 * Runs the Whole evaluation.
	 */
	public function run()
	{
		if (!$this->supposedActionCalled && $this->actualValue === $this->expectedValue) {
			$this->call($this->supposedAction);

			$this->supposedActionCalled = true;
		} else {
			if ($this->fallbackAction instanceof Closure) {
				$this->call($this->fallbackAction);
			}
		}
	}
}
