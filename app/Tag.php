<?php

namespace Spectator;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded = ['id', 'updated_at', 'created_at'];

    public function videos()
    {
        return $this->morphedByMany('App\Video', 'taggable');
    }

    public function creators()
    {
        return $this->morphedByMany('App\Creator', 'taggable');
    }

    public function series()
    {
        return $this->morphedByMany('App\Series', 'taggable');
    }

    public function games()
    {
        return $this->morphedByMany('App\Game', 'taggable');
    }
}
