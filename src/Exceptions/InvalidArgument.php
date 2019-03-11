<?php

declare(strict_types=1);

namespace Geocoding\Laravel\Exceptions;

use Geocoding\Laravel\Exception\Exception;

class InvalidArgument extends \InvalidArgumentException implements Exception
{

}