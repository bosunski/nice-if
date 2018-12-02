<?php

use Neater\Condition\Condition;

/**
 * Provided a public accessor for the Condition class
 * @param $task
 *
 * @return Condition
 */
function perform($task) {
	$condition = new Condition;

	return $condition->perform($task);
}