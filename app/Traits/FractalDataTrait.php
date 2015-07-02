<?php

namespace Spectator\Traits;

use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection as DbCollection;

trait FractalDataTrait {

	protected function createCollectionData(DbCollection $data, TransformerAbstract $transformer)
	{
		if($this->request->has('include')) {
		    $this->fractal->parseIncludes($this->request->include);
		}

		$collection = new Collection($data, $transformer);
		return $this->fractal->createData($collection)->toArray();
	}

	protected function createItemData(Model $data, TransformerAbstract $transformer)
	{
		if($this->request->has('include')) {
		    $this->fractal->parseIncludes($this->request->include);
		}

		$item = new Item($data, $transformer);
		return $this->fractal->createData($item)->toArray();
	}
}