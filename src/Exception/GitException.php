<?php

namespace GrumPHP\Exception;

class GitException extends RuntimeException
{
    /**
     * @return GitException
     */
    public static function couldNotFindTopLevel()
    {
        return new self('The git folder could not be found. Did you git init?');
    }
}
