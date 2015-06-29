<?php

namespace Spectator;

use Illuminate\Database\Eloquent\Model;

class Series extends Model {

	public function game()
	{
		return $this->belongsTo('Spectator\Game');
	}

	public function videos()
	{
		return $this->belongsToMany('Spectator\Video');
	}

}