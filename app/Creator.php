<?php

namespace Spectator;

use Illuminate\Database\Eloquent\Model;

class Creator extends Model {

	protected $guarded = ['id', 'updated_at', 'created_at'];

	public function videos()
	{
		return $this->hasMany('Spectator\Video');
	}

	public function series()
	{
		return $this->hasMany('Spectator\Series');
	}

	public function games()
	{
		return $this->belongsToMany('Spectator\Game');
	}

	public function user()
	{
		return $this->belongsTo('Spectator\User');
	}

	public function tags()
	{
		return $this->morphToMany('Spectator\Tag', 'taggable');
	}
}