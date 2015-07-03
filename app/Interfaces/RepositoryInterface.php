<?php

namespace Spectator\Interfaces;

interface RepositoryInterface {

	public function getAll();
	public function get($id);
}