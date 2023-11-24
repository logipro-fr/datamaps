<?php

namespace Datamaps\Domain\Model\Map\Exceptions;

class MapNotFoundException extends LoggedException
{
    public const ERROR_CODE = 404;
}
