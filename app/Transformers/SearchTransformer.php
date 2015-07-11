<?php

namespace Spectator\Transformers;

use League\Fractal\TransformerAbstract;

class SearchTransformer extends TransformerAbstract {

	protected $availableIncludes = [

	];

	public function transform($searchResult) {

		return [
			"result" => "search"
		];
	}
}