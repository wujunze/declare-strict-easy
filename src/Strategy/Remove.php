<?php

declare(strict_types=1);

namespace Dypa\DeclareStrictTypes\Strategy;

class Remove extends AbstractStrategy implements StrategyInterface
{
    public function __invoke(string $sourceCode): string
    {
        return preg_replace(
            '/(\s*)<\?php(\s*)declare\s*\(\s*strict_types\s*=\s*\d\s*\)\s*;\s*/i',
            '$1<?php$2',
            $sourceCode,
            1,
            $this->isAffected
        );
    }
}
