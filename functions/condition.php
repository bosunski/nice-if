<?php

use Neater\Condition\Condition;

function perform($task) {
	$condition = new Condition;

	return $condition->perform($task);
}