<?php

namespace GrumPHP\Locator;

use GrumPHP\Collection\ProcessArgumentsCollection;
use GrumPHP\Exception\GitException;
use GrumPHP\Process\ProcessBuilder;

class GitTopLevelLocator
{

    /**
     * @var ProcessBuilder
     */
    private $processBuilder;

    /**
     * GitTopLevelLocator constructor.
     *
     * @param ProcessBuilder $processBuilder
     */
    public function __construct(ProcessBuilder $processBuilder)
    {
        $this->processBuilder = $processBuilder;
    }

    /**
     * @return string
     * @throws GitException
     */
    public function locate()
    {
        try {
            $arguments = ProcessArgumentsCollection::forExecutable('git');
            $arguments->add('rev-parse');
            $arguments->add('--show-toplevel');

            $process = $this->processBuilder->buildProcess($arguments);
            $process->run();
        } catch (\Exception $e) {
            throw GitException::fromAnyException($e);
        }

        if (!$process->isSuccessful()) {
            throw GitException::couldNotFindTopLevel();
        }

        return $process->getOutput();
    }
}
