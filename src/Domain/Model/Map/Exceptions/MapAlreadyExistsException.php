<?php

namespace Datamaps\Domain\Model\Map\Exceptions;

class MapAlreadyExistsException extends LoggedException
{
    public const ERROR_CODE = 409;
}
