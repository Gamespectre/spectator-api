<?php

namespace Spectator\Services\Youtube;

class YoutubeApiPager {

	private $token;
	private $chunk;
	private $page = 0;
	private $total = 9999;
	private $stuffLeft = 0;
	private $totalToGet = 0;
	private $lastFetched = 0;
	private $totalFetched = 0;
	private $absoluteTotal = false;

	public function __construct($totalToGet, $chunkSize = false, $absoluteTotal = false)
	{
        $chunk = !$chunkSize ? $totalToGet : $chunkSize;

		$this->chunk = $chunk;
		$this->totalToGet = $totalToGet;

		if($absoluteTotal === true) {
			$this->total = $chunk;
			$this->absoluteTotal = true;
		}
	}

	public function page(callable $action)
	{
		while($this->totalFetched < $this->getTotal()) {
			$data = $action($this);

			if($data === false) {
				continue;
			}

			$this->update($data);

			if($this->shouldStop()) {
				break;
			}
		}
	}

	public function update($data)
	{
		$this->lastFetched = (int) $data['pageInfo']['resultsPerPage'];
		$this->token = $data['nextPageToken'];
		$this->total = $this->absoluteTotal === false ? (int) $data['pageInfo']['totalResults'] : $this->totalToGet;

		$this->totalFetched += $this->lastFetched;
		$this->stuffLeft = $this->total - $this->totalFetched;

		$this->page++;
	}

	public function getPage()
	{
		return $this->page;
	}

	public function getChunk()
	{
		return $this->stuffLeft < $this->chunk ? $this->stuffLeft : $this->chunk;
	}

	public function getTotal()
	{
		return $this->absoluteTotal === true ? $this->totalToGet : $this->total;
	}

	public function getLastFetched()
	{
		return $this->lastFetched;
	}

	public function getToken()
	{
		return $this->token;
	}

	public function shouldStop()
	{
		return is_null($this->getToken()) || $this->stuffLeft <= 0;
	}
}