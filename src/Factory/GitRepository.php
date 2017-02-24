<?php

namespace GrumPHP\Factory;

use Gitonomy\Git\Repository;
use GrumPHP\Locator\GitTopLevelLocator;
use Psr\Log\LoggerInterface;

class GitRepository
{
    /**
     * @param GitTopLevelLocator $gitTopLevelLocator
     * @param LoggerInterface $logger
     *
     * @return Repository
     *
     * @throws \GrumPHP\Exception\GitException
     * @throws \Gitonomy\Git\Exception\InvalidArgumentException
     */
    public static function factory(GitTopLevelLocator $gitTopLevelLocator, LoggerInterface $logger)
    {
        return new Repository(
            $gitTopLevelLocator->locate(),
            [
                'logger' => $logger,
            ]
        );
    }
}
