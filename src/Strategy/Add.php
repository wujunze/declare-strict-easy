<?php

declare(strict_types=1);

namespace Dypa\DeclareStrictTypes\Strategy;

class Add extends AbstractStrategy implements StrategyInterface
{
    public function __invoke(string $sourceCode): string
    {
        if (preg_match(
            '/\s*<\?php\s*declare\s*\(\s*strict_types\s*=\s*\d\s*\)\s*;/i',
            $sourceCode

        )) {
            return $sourceCode;
        }

        return preg_replace(
            '/(\s*)<\?php(\s*)/i',
            "$1<?php$2declare(strict_types=1);\n",
            $sourceCode,
            1,
            $this->isAffected
        );
    }
}
