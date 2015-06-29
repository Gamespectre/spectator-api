<?php

namespace Spectator\Lib\Interfaces;

interface RepositoryInterface {

	public function getAll();
	public function get($id);
	public function createModel($data);
}