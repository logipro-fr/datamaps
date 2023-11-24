<?php

namespace Datamaps\Domain\Model\Map\Exceptions;

class EmptyRepositoryException extends LoggedException
{
    public const ERROR_CODE = 422;
}
