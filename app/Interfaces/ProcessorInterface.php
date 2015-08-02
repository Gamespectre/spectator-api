<?php

namespace Spectator\Interfaces;

interface ProcessorInterface
{
    public function execute();
    public function getPipeline();
}