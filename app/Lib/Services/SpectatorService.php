<?php

namespace Spectator\Lib\Services;

use Debugbar;

abstract class SpectatorService {

	public $cli = false;

	public function consoleLog($message, $method)
	{
		if($this->cli !== false) {
			call_user_func([$this->cli, $method], $message);
		}
		else {
			call_user_func(['Debugbar', $method], $message);
		}
	}
}