<?php

namespace Spectator;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {

	protected $guarded = ['id', 'updated_at', 'created_at'];

	public function users()
	{
		return $this->belongsToMany('Spectator\User');
	}
}