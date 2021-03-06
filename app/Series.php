<?php

namespace Spectator;

use Illuminate\Database\Eloquent\Model;

class Series extends Model {

	protected $guarded = ['id', 'updated_at', 'created_at'];

	public function game()
	{
		return $this->belongsTo('Spectator\Game');
	}

	public function videos()
	{
		return $this->belongsToMany('Spectator\Video');
	}

	public function creator()
	{
		return $this->belongsTo('Spectator\Creator');
	}

	public function tags()
	{
		return $this->morphToMany('Spectator\Tag', 'taggable');
	}

}