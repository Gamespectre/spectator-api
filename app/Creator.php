<?php

namespace Spectator;

use Illuminate\Database\Eloquent\Model;

class Creator extends Model {

	public function videos()
	{
		return $this->hasMany('Spectator\Video');
	}
}