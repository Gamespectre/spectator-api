<?php

namespace Spectator\Services\App;

use Illuminate\Support\Collection;

class GenericPackage extends Package
{
    /**
     * This is a generic package, used mainly for testing.
     */

    public function saveAll()
    {
        // Not impolemented for generic package.
    }

    public function saveOnly(Collection $data)
    {
        // Not implemented
    }
}