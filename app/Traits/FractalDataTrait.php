<?php

namespace Spectator\Traits;

use Illuminate\Database\Eloquent\Collection as DbCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

trait FractalDataTrait {

	protected function createCollectionData(DbCollection $data, TransformerAbstract $transformer)
	{
        $collection = new Collection($data, $transformer);
		return $this->fractal->createData($collection)->toArray();
	}

    protected function createPagedCollection(LengthAwarePaginator $paginator, TransformerAbstract $transformer)
    {
        $data = $paginator->getCollection();
        $collection = new Collection($data, $transformer);
        $collection->setPaginator(new IlluminatePaginatorAdapter($paginator));
        return $this->fractal->createData($collection)->toArray();
    }

	protected function createItemData(Model $data, TransformerAbstract $transformer)
	{
		$item = new Item($data, $transformer);
		return $this->fractal->createData($item)->toArray();
	}
}