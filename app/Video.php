<?php

namespace Spectator;

use Illuminate\Database\Eloquent\Model;

class Video extends Model {

	protected $guarded = ['id', 'updated_at', 'created_at'];

    protected $touches = ['series'];

	public function creator()
	{
		return $this->belongsTo('Spectator\Creator');
	}

	public function game()
	{
		return $this->belongsTo('Spectator\Game');
	}

	public function series()
	{
		return $this->belongsToMany('Spectator\Series');
	}
}