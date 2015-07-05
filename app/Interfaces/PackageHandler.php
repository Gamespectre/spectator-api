<?php

namespace Spectator\Interfaces;

use Spectator\Services\App\Package;

interface PackageHandler
{
    public function pack(Package $package);
    public function getData();
}