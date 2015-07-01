<?php

namespace Spectator;

use Illuminate\Database\Eloquent\Model;

class Creator extends Model {

	protected $guarded = ['id', 'updated_at', 'created_at'];

	public function videos()
	{
		return $this->hasMany('Spectator\Video');
	}
}