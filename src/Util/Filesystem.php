<?php

declare(strict_types=1);

namespace GrumPHP\Util;

use GrumPHP\Configuration\GrumPHP;
use SplFileInfo;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

class Filesystem extends SymfonyFilesystem
{
    /**
     * @var GrumPHP
     */
    private $config;

    public function __construct(GrumPHP $config)
    {
        $this->config = $config;
    }

    public function readFromFileInfo(SplFileInfo $file): string
    {
        $handle = $file->openFile('r');
        $content = '';
        while (!$handle->eof()) {
            $content .= $handle->fgets();
        }

        return $content;
    }

    public function getWorkingDir(): string
    {
        return getcwd();
    }

    public function getRelativeWorkingDirInGitDir(): string
    {
        return $this->makePathRelative($this->getWorkingDir(), $this->config->getGitDir());

    }

    public function getRelativeGitDir(): string
    {
        return $this->makePathRelative($this->getWorkingDir(), realpath($this->config->getGitDir()));
    }
}
