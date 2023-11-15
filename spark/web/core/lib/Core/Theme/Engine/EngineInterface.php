<?php

namespace Nulldark\Template;

interface EngineInterface
{
    public function render(): string;
}