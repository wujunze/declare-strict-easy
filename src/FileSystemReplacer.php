<?php

declare(strict_types=1);

namespace Dypa\DeclareStrictTypes;

use Dypa\DeclareStrictTypes\Strategy\StrategyInterface;
use Symfony\Component\Finder\Finder;

class FileSystemReplacer
{
    private $includeDirectories;
    private $excludeDirectories;

    private $affectedFiles = [];

    public function __construct(array $includeDirectories, array $excludeDirectories)
    {
        $this->includeDirectories = $includeDirectories;
        $this->excludeDirectories = $excludeDirectories;
    }

    private function getFilesList(): \Iterator
    {
        $finder = new Finder();
        $finder->in($this->includeDirectories);
        $finder->exclude($this->excludeDirectories);
        $finder->files();
        $finder->followLinks();
        $finder->name('*.php');

        return $finder->getIterator();
    }

    private function fileIterator(\Iterator $iterator, StrategyInterface $callback)
    {
        foreach ($iterator as $file) {
            /** @var $file \SplFileInfo */
            $filePath = $file->getRealPath();
            file_put_contents(
                $filePath,
                $callback(file_get_contents($filePath))
            );
            if ($callback->getIsAffected()) {
                $this->affectedFiles[] = $filePath;
            }
        }
    }

    public function replace(StrategyInterface $callback)
    {
        $this->fileIterator($this->getFilesList(), $callback);
    }

    public function getAffectedFiles():array
    {
        return $this->affectedFiles;
    }
}
