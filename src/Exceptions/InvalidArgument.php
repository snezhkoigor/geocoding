<?php

declare(strict_types=1);

namespace Geocode\Laravel\Exceptions;

use Geocode\Laravel\Exception\Exception;

class InvalidArgument extends \InvalidArgumentException implements Exception
{

}