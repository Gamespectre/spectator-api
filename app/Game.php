<?php

namespace Spectator;

use Illuminate\Database\Eloquent\Model;

class Game extends Model {

	protected $guarded = ['id', 'updated_at', 'created_at'];

	public function videos()
	{
		return $this->hasMany('Spectator\Video');
	}

	public function series()
	{
		return $this->hasMany('Spectator\Series');
	}

	public function creators()
	{
		return $this->belongsToMany('Spectator\Creator');
	}

	public function tags()
	{
		return $this->morphToMany('Spectator\Tag', 'taggable');
	}
}