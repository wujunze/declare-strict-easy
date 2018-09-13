<?php

declare(strict_types=1);

namespace Dypa\DeclareStrictTypes\Strategy;

interface StrategyInterface
{
    public function __invoke(string $sourceCode):string;
}
