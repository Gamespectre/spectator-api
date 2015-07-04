<?php

namespace Spectator\Datamodels;

use Illuminate\Support\Collection;

abstract class Datamodel {

	public $model = false;
	protected $_internalData = [];

	public function __construct($data = [])
	{
		if(!empty($data)) {
			$this->create($data);
		}
	}

	public function create($data)
	{
		if(is_array($data)) {
            $data = $data[0];
        }

		$this->_internalData = collect($this->transform($data));

        $this->getUniqueModel();

        return $this;
	}

	public static function createFromItem($data) {
		return new static($data);
	}

	public static function createFromCollection(Collection $rawCollection)
	{
		return $rawCollection->map(function($item, $key) {
            return new static($item);
        });
	}

	public function persist()
	{
		if($this->model !== false) {
			return $this->model;
		}

		$props = [];

		$this->_internalData->each(function($item, $key) use (&$props) {

			// If we have a designated db column name, use that.
			if(isset($item[1]) && $item[1] !== false)
			{
				$props[$item[1]] = $item[0];
			}

			// Else, use the original key.
			else if(!isset($item[1]))
            {
				$props[$key] = $item[0];
			}
		});

		$this->model = (new $this->modelClass)->create($props);
		return $this->model;
	}

	public function isPersisted()
	{
		return $this->model !== false;
	}

	protected function getUniqueModel()
	{
        if($this->model !== false) {
            return $this;
        }

		$model = (new $this->modelClass)->where($this->uniqueKey, $this->id)->first();
		$this->model = is_null($model) ? false : $model;

		return $this;
	}

	public function __get($prop)
	{
		if(property_exists($this, $prop)) {
			return $this->$prop;
		}

		return $this->_internalData->get($prop)[0];
	}

	public function __set($prop, $value)
	{
		if(property_exists($this, $prop)) {
			$this->$prop = $value;
		}

        if($this->_internalData->has($prop)) {
            $item = $this->_internalData->get($prop);
            $dbColumn = isset($item[1]) ? $item[1] : $item[0];

            $this->_internalData->put($prop, [$value, $dbColumn]);
        }
	}

	public function __toString()
	{
		return $this->_internalData->put("model", $this->model)->toJson();
	}

	abstract public function transform($raw);
}