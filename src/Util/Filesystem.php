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

    public function getProjectDir(): string
    {
        return dirname($this->config->getConfigFile());
    }

    public function getGitDir(): string
    {
        return realpath($this->config->getGitDir());
    }

    public function getRelativeProjectDir(): string
    {
        return rtrim('/', $this->makePathRelative(
            $this->getProjectDir(),
            $this->getGitDir()
        ));
    }
}
