<?php

namespace Spectator\Interfaces;

use Spectator\Services\App\Package;

interface HandlesPackages
{
    public function handlePackage(Package $package);
}